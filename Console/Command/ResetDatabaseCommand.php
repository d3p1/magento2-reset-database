<?php
/**
 * @description Reset database command
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace Bina\ResetDatabase\Console\Command;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Indexer\Model\IndexerFactory;
use Magento\Indexer\Model\Indexer;
use Bina\ResetDatabase\Helper\Console\Command\ResetDatabaseCommand as ResetDatabaseCommandHelper;

class ResetDatabaseCommand extends Command
{
    /**
     * @const ARGUMENT_ENTITY_KEY
     */
    const ARGUMENT_ENTITY_KEY = 'entity';

    /**
     * @const OPTION_EXTRA_TABLE_KEY
     */
    const OPTION_EXTRA_TABLE_KEY = 'extra-table';

    /**
     * @var ResetDatabaseCommandHelper
     */
    protected $_helper;

    /**
     * @var IndexerFactory
     */
    private $_indexerFactory;

    /**
     * @var ResourceConnection
     */
    private $_resourceConnection;

    /**
     * Constructor
     *
     * @param ResetDatabaseCommandHelper $helper
     * @param IndexerFactory             $indexerFactory
     * @param ResourceConnection         $resourceConnection
     */
    public function __construct(
        ResetDatabaseCommandHelper $helper,
        IndexerFactory             $indexerFactory,
        ResourceConnection         $resourceConnection
    ) {
        /**
         * @note Set resource connection to do direct SQL queries
         * @note We use direct SQL queries to improve command performance
         */
        $this->_resourceConnection = $resourceConnection;
        $this->_helper             = $helper;
        $this->_indexerFactory     = $indexerFactory;

        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('setup:db-data:reset')
             ->setDescription('Reset database command');

        $this->addArgument(
            self::ARGUMENT_ENTITY_KEY,
            InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
            'Space-separated list of entities to reset (or omit to reset all entities).'
        );

        $this->addOption(
            self::OPTION_EXTRA_TABLE_KEY,
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Space-separated list of tables to reset.'
        );

        parent::configure();
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Reset entities:</info>');

        $entities = $this->_getEntities($input);
        foreach ($entities as $entity) {
            $this->_deleteTablesData($this->_helper->getTablesToDeleteDataFromEntity($entity), $output);

            $this->_resetTablesAutoIncrementKey($this->_helper->getTablesToResetAutoIncrementKeyFromEntity($entity), $output);

            $this->_reindex($this->_helper->getIndexersFromEntity($entity), $output);
        }

        $extraTables = $input->getOption(self::OPTION_EXTRA_TABLE_KEY);

        $this->_deleteTablesData($extraTables, $output);

        $this->_resetTablesAutoIncrementKey($extraTables, $output);
    }

    /**
     * Return entities to reset
     *
     * @param  InputInterface $input
     * @return array
     * @throws InvalidArgumentException
     */
    protected function _getEntities(InputInterface $input)
    {
        $requestedEntities = [];
        if ($input->getArgument(self::ARGUMENT_ENTITY_KEY)) {
            $requestedEntities = $input->getArgument(self::ARGUMENT_ENTITY_KEY);
            $requestedEntities = array_filter(array_map('trim', $requestedEntities), 'strlen');
        }

        if (empty($requestedEntities)) {
            $requestedEntities = $this->_helper->getAvailableEntities();
        }
        else {
            $availableEntities = $this->_helper->getAvailableEntities();

            $unsupportedEntities = array_diff($requestedEntities, $availableEntities);
            if ($unsupportedEntities) {
                throw new InvalidArgumentException(
                    'The following requested entities are not supported: ' .
                    join(', ', $unsupportedEntities)                       .
                    '.'                                                    .
                    PHP_EOL                                                .
                    'Supported types: '                                    .
                    join(', ', $availableEntities));
            }
        }

        return $requestedEntities;
    }

    /**
     * Delete tables data
     *
     * @param  array           $tables
     * @param  OutputInterface $output
     * @return void
     */
    protected function _deleteTablesData($tables, $output)
    {
        foreach ($tables as $table) {
            $tableName = $table;
            $condition = '';

            if (is_array($table)) {
                $tableName = $table['table'];
                $condition = $table['condition'];
            }

            $output->writeln('<info>Delete data from table: ' . $tableName . '</info>');

            $this->_deleteTableData($tableName, $condition);
        }
    }

    /**
     * Reset tables auto increment key
     *
     * @param  array           $tables
     * @param  OutputInterface $output
     * @return void
     */
    protected function _resetTablesAutoIncrementKey($tables, $output)
    {
        /**
         * @note Loop tables to reset their auto increment key
         */
        foreach ($tables as $table) {
            $output->writeln(
                '<info>Reset auto increment key from table: ' . $table . '</info>');
            $this->_resetAutoIncrementKey($table);
        }
    }

    /**
     * Reindex data
     *
     * @param  array           $indexers
     * @param  OutputInterface $output
     * @return void
     */
    protected function _reindex($indexers, $output)
    {
        foreach ($indexers as $indexerId) {
            $output->writeln('<info>Reindex: ' . $indexerId . '</info>');

            /** @var Indexer $indexer */
            $indexer = $this->_indexerFactory->create();
            $indexer->load($indexerId);
            $indexer->reindexAll();
        }
    }

    /**
     * Delete table data
     *
     * @param  string $table Table name
     * @param  string $where Filters condition
     * @return void
     */
    private function _deleteTableData($table, $where = '')
    {
        $connection = $this->_resourceConnection->getConnection();
        $connection->delete($connection->getTableName($table), $where);
    }

    /**
     * Reset auto increment key
     *
     * @param  string $table Table name
     * @return void
     */
    private function _resetAutoIncrementKey($table)
    {
        $connection = $this->_resourceConnection->getConnection();
        $connection->query(sprintf('ALTER TABLE %s AUTO_INCREMENT = 1', $connection->getTableName($table)));
    }
}
