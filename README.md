# MageReactor BundleExtended For Magento 2

Magento 2 module that provides Budle of Configurable Products

## Install

### Composer

```bash
composer require magereactor/bundle-extended
```

### Enable Module

```php
php bin/magento module:enable MageReactor_BundleExtended
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
```

You may need to Flush Magento Cache after installation.
