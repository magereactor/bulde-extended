# MageReactor BundleExtended For Magento 2

Magento 2 module that provides Budle of Configurable Products

## Install

### Composer

```bash
composer require magereactor/bundle-extended
```

### Compatiblity
Currently this module is compatible with Magento 2.4.x


### Enable Module

```php
php bin/magento module:enable MageReactor_BundleExtended
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
```

You may need to Flush Magento Cache after installation.
