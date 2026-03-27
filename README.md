<div align=center>

# [MAGENTO 2 - RESET DATABASE]

[![code style: prettier](https://img.shields.io/badge/code_style-prettier-ff69b4.svg)](https://github.com/prettier/prettier)
[![code consistency: php_code_sniffer](https://img.shields.io/badge/code_consistency-php_code_sniffer-blue.svg)](https://github.com/PHPCSStandards/PHP_CodeSniffer)
[![code consistency: php_cs_fixer](https://img.shields.io/badge/code_consistency-php_cs_fixer-purple.svg)](http://github.com/PHP-CS-Fixer/PHP-CS-Fixer)

</div>

## Introduction

A [Magento 2](https://business.adobe.com/products/magento/magento-commerce.html) implementation that allows you to reset a Magento database to its initial state.

## Installation

To proceed with the installation of your module, you need to go to the [document root of your Magento installation](https://experienceleague.adobe.com/docs/commerce-operations/installation-guide/tutorials/docroot.html) and run the following commands:

```
composer require d3p1/module-reset-database
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
bin/magento cache:clean
bin/magento cache:flush
```

> [!NOTE]
> To execute `bin/magento setup:static-content:deploy` you need to list all the languages of your store/s using the ISO-639 format. Also, if you are using a platform mode different than `production`, you need to execute the command using the `-f` flag: [`bin/magento setup:static-content:deploy -f`](https://devdocs.magento.com/guides/v2.4/config-guide/cli/config-cli-subcommands-static-view.html).

> [!NOTE]
> The command `bin/magento cache:flush` is [optional](https://devdocs.magento.com/guides/v2.4/config-guide/cli/config-cli-subcommands-cache.html#config-cli-subcommands-cache-clean).

## Changelog

Detailed changes for each release are documented in [`CHANGELOG.md`](./CHANGELOG.md).

## License

This work is published under [MIT License](./LICENSE).

## Author

Always happy to receive a greeting on:

- [LinkedIn](https://www.linkedin.com/in/cristian-marcelo-de-picciotto/)
- [Web](https://d3p1.dev/)
