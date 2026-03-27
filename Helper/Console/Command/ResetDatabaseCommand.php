<?php
/**
 * @description Reset database command helper
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace Bina\ResetDatabase\Helper\Console\Command;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Customer\Model\Customer;
use Magento\Catalog\Model\Indexer\Category\Product                 as CategoryProductIndexer;
use Magento\Catalog\Model\Indexer\Product\Category                 as ProductCategoryIndexer;
use Magento\Catalog\Model\Indexer\Product\Price\Processor          as PriceIndexer;
use Magento\Catalog\Model\Indexer\Product\Eav\Processor            as EavIndexer;
use Magento\CatalogInventory\Model\Indexer\Stock\Processor         as StockIndexer;
use Magento\CatalogRule\Model\Indexer\Rule\RuleProductProcessor    as RuleProductIndexer;
use Magento\CatalogRule\Model\Indexer\Product\ProductRuleProcessor as ProductRuleIndexer;

class ResetDatabaseCommand extends AbstractHelper
{
    /**
     * @const CUSTOMER_ENTITY_KEY
     */
    const CUSTOMER_ENTITY_KEY = 'customer';

    /**
     * @const CATEGORY_ENTITY_KEY
     */
    const CATEGORY_ENTITY_KEY = 'category';

    /**
     * @const PRODUCT_ENTITY_KEY
     */
    const PRODUCT_ENTITY_KEY = 'product';

    /**
     * @const URL_REWRITE_KEY
     */
    const URL_REWRITE_KEY = 'url_rewrite';

    /**
     * @const CATALOGRULE_KEY
     */
    const CATALOGRULE_KEY = 'catalogrule';

    /**
     * @const CARTRULE_KEY
     */
    const CARTRULE_KEY = 'cartrule';

    /**
     * @const QUOTE_ENTITY_KEY
     */
    const QUOTE_ENTITY_KEY = 'quote';

    /**
     * @const ORDER_ENTITY_KEY
     */
    const ORDER_ENTITY_KEY = 'order';

    /**
     * @const DELETE_TABLE_DATA_KEY
     */
    const DELETE_TABLE_DATA_KEY = 'delete';

    /**
     * @const RESET_TABLE_AI_KEY
     */
    const RESET_TABLE_AI_KEY = 'reset_ai';

    /**
     * @const INDEXER_KEY
     */
    const INDEXER_KEY = 'indexer';

    /**
     * @var array
     */
    private $_entityTables = [
        self::CUSTOMER_ENTITY_KEY => [
            self::DELETE_TABLE_DATA_KEY => [
                'customer_entity'
            ],
            self::RESET_TABLE_AI_KEY => [
                'customer_entity',
                'customer_entity_datetime',
                'customer_entity_decimal',
                'customer_entity_int',
                'customer_entity_text',
                'customer_entity_varchar',
                'customer_log',
                'customer_visitor',
                'customer_address_entity',
                'customer_address_entity_datetime',
                'customer_address_entity_decimal',
                'customer_address_entity_int',
                'customer_address_entity_text',
                'customer_address_entity_varchar'
            ],
            self::INDEXER_KEY => [
                Customer::CUSTOMER_GRID_INDEXER_ID
            ]
        ],
        self::CATEGORY_ENTITY_KEY => [
            self::DELETE_TABLE_DATA_KEY => [
                [
                    'table'     => 'catalog_category_entity',
                    'condition' => 'level > 1'
                ]
            ],
            self::RESET_TABLE_AI_KEY => [
                'catalog_category_entity',
                'catalog_category_entity_datetime',
                'catalog_category_entity_decimal',
                'catalog_category_entity_int',
                'catalog_category_entity_text',
                'catalog_category_entity_varchar',
                'catalog_category_product'
            ],
            self::INDEXER_KEY => [
                CategoryProductIndexer::INDEXER_ID
            ]
        ],
        self::PRODUCT_ENTITY_KEY => [
            self::DELETE_TABLE_DATA_KEY => [
                'catalog_product_entity'
            ],
            self::RESET_TABLE_AI_KEY => [
                'catalog_compare_item',
                'catalog_product_bundle_option',
                'catalog_product_bundle_option_value',
                'catalog_product_bundle_selection',
                'catalog_product_bundle_selection_price',
                'catalog_product_entity',
                'catalog_product_entity_datetime',
                'catalog_product_entity_decimal',
                'catalog_product_entity_gallery',
                'catalog_product_entity_int',
                'catalog_product_entity_media_gallery',
                'catalog_product_entity_text',
                'catalog_product_entity_tier_price',
                'catalog_product_entity_varchar',
                'catalog_product_frontend_action',
                'catalog_product_link',
                'catalog_product_link_attribute',
                'catalog_product_link_attribute_decimal',
                'catalog_product_link_attribute_int',
                'catalog_product_link_attribute_varchar',
                'catalog_product_option',
                'catalog_product_option_price',
                'catalog_product_option_title',
                'catalog_product_option_type_price',
                'catalog_product_option_type_title',
                'catalog_product_option_type_value',
                'catalog_product_super_attribute',
                'catalog_product_super_attribute_label',
                'catalog_product_super_link',
                'cataloginventory_stock_item',
                'inventory_reservation',
                'inventory_source_item'
            ],
            self::INDEXER_KEY => [
                ProductCategoryIndexer::INDEXER_ID,
                PriceIndexer::INDEXER_ID,
                EavIndexer::INDEXER_ID,
                StockIndexer::INDEXER_ID
            ]
        ],
        self::URL_REWRITE_KEY => [
            self::DELETE_TABLE_DATA_KEY => [
                'url_rewrite'
            ],
            self::RESET_TABLE_AI_KEY => [
                'url_rewrite'
            ],
            self::INDEXER_KEY => []
        ],
        self::CATALOGRULE_KEY => [
            self::DELETE_TABLE_DATA_KEY => [
                'catalogrule'
            ],
            self::RESET_TABLE_AI_KEY => [
                'catalogrule',
                'catalogrule_product',
                'catalogrule_product_price',
                'catalogrule_product_price_replica',
                'catalogrule_product_replica'
            ],
            self::INDEXER_KEY => [
                RuleProductIndexer::INDEXER_ID,
                ProductRuleIndexer::INDEXER_ID
            ]
        ],
        self::CARTRULE_KEY => [
            self::DELETE_TABLE_DATA_KEY => [
                'salesrule'
            ],
            self::RESET_TABLE_AI_KEY => [
                'salesrule',
                'salesrule_coupon',
                'salesrule_coupon_aggregated',
                'salesrule_coupon_aggregated_order',
                'salesrule_coupon_aggregated_updated',
                'salesrule_customer',
                'salesrule_label'
            ],
            self::INDEXER_KEY => []
        ],
        self::QUOTE_ENTITY_KEY => [
            self::DELETE_TABLE_DATA_KEY => [
                'quote'
            ],
            self::RESET_TABLE_AI_KEY => [
                'quote',
                'quote_address',
                'quote_address_item',
                'quote_id_mask',
                'quote_item',
                'quote_item_option',
                'quote_payment',
                'quote_shipping_rate'
            ],
            self::INDEXER_KEY => []
        ],
        self::ORDER_ENTITY_KEY => [
            self::DELETE_TABLE_DATA_KEY => [
                'sales_order',
                'sales_order_grid',
                'sales_invoice_grid',
                'sales_shipment_grid',
                'sales_creditmemo_grid'
            ],
            self::RESET_TABLE_AI_KEY => [
                'sales_creditmemo',
                'sales_creditmemo_comment',
                'sales_creditmemo_item',
                'sales_invoice',
                'sales_invoice_comment',
                'sales_invoice_item',
                'sales_invoiced_aggregated',
                'sales_invoiced_aggregated_order',
                'sales_order',
                'sales_order_address',
                'sales_order_aggregated_created',
                'sales_order_aggregated_updated',
                'sales_order_item',
                'sales_order_payment',
                'sales_order_status_history',
                'sales_order_tax',
                'sales_order_tax_item',
                'sales_payment_transaction',
                'sales_refunded_aggregated',
                'sales_refunded_aggregated_order',
                'sales_shipment',
                'sales_shipment_comment',
                'sales_shipment_item',
                'sales_shipment_track',
                'sales_shipping_aggregated',
                'sales_shipping_aggregated_order'
            ],
            self::INDEXER_KEY => []
        ]
    ];

    /**
     * Get available entities
     *
     * @return array
     */
    public function getAvailableEntities()
    {
        return array_keys($this->_entityTables);
    }

    /**
     * Get entity tables to delete their data
     *
     * @param  string $entity
     * @return array
     */
    public function getTablesToDeleteDataFromEntity($entity)
    {
        return $this->_entityTables[$entity][self::DELETE_TABLE_DATA_KEY];
    }

    /**
     * Get entity tables to reset their auto increment key
     *
     * @param  string $entity
     * @return array
     */
    public function getTablesToResetAutoIncrementKeyFromEntity($entity)
    {
        return $this->_entityTables[$entity][self::RESET_TABLE_AI_KEY];
    }

    /**
     * Get indexers from entity
     *
     * @param  string $entity
     * @return array
     */
    public function getIndexersFromEntity($entity)
    {
        return $this->_entityTables[$entity][self::INDEXER_KEY];
    }
}