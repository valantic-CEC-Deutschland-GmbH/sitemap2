# Sitemap:

## Installation

1. Install dependency
```
composer require valantic-spryker/sitemap
```

2. Make sure that ValanticSpryker namespace is registered in `config_default.php`

```php
$config[KernelConstants::CORE_NAMESPACES] = [
    'SprykerShop',
    'SprykerEco',
    'Spryker',
    'ValanticSpryker',
];
```

3. Register RouterPlugin
```php
<?php

namespace Pyz\Yves\Router;

use [...]

class RouterDependencyProvider extends SprykerRouterDependencyProvider
{
    [...]

    /**
     * @return \Spryker\Yves\RouterExtension\Dependency\Plugin\RouteProviderPluginInterface[]
     */
    protected function getRouteProvider(): array
    {
        return [
            [...]
            new SitemapControllerProvider(),
        ];
    }
}
```

3. Register Console command
```php
<?php
declare(strict_types = 1);

namespace Pyz\Zed\Console;

use [...]

/**
 * @method \Pyz\Zed\Console\ConsoleConfig getConfig()
 */
class ConsoleDependencyProvider extends SprykerConsoleDependencyProvider
{
    [...]

     /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    protected function getConsoleCommands(Container $container)
    {
        $commands = [
            [...]
            new SitemapGenerateConsole(),
        ];
    }
}
```

5. Add cronjobs in `jenkins.php` for each store

```php
$jobs[] = [
    'name' => 'generate-sitemap-de',
    'command' => 'APPLICATION_STORE=DE $PHP_BIN vendor/bin/console sitemap:generate -vvv',
    'schedule' => '0 0 1 1 *',
    'enable' => false,
    'run_on_non_production' => true,
    'stores' => $allStores,
];
$jobs[] = [
    'name' => 'generate-sitemap-at',
    'command' => 'APPLICATION_STORE=AT $PHP_BIN vendor/bin/console sitemap:generate -vvv',
    'schedule' => '0 0 1 1 *',
    'enable' => false,
    'run_on_non_production' => true,
    'stores' => $allStores,
];
// add jobs for each store
```

When executing sitemap command from console, make sure to use the following syntax, in order to have correct base URLs:

`APPLICATION_STORE=AT docker/sdk cli console sitemap:generate -vvv`

6. You can optionally add sitemap url limit per one XML file in `config_default`. The default is 50000.

```php
$config[SitemapConstants::SITEMAP_URL_LIMIT] = 50000;
```

7. Register connector modules to see resources such as category, product abstract urls. Information is provided in each
   connector module:
   1. https://github.com/valantic-CEC-Deutschland-GmbH/category-sitemap-connector
   2. https://github.com/valantic-CEC-Deutschland-GmbH/content-pages-sitemap-connector
   3. https://github.com/valantic-CEC-Deutschland-GmbH/product-abstract-sitemap-connector
   4. https://github.com/valantic-CEC-Deutschland-GmbH/product-concrete-sitemap-connector
   5. https://github.com/valantic-CEC-Deutschland-GmbH/merchant-sitemap-connector

8. If you want to retrieve sitemap data from Redis instead of DB, install `sitemap-storage` module:
   1. https://github.com/valantic-CEC-Deutschland-GmbH/sitemap-storage

## Access Sitemap

The index of sitemap is `/sitemap.xml`, so for example on demo shop that would be http://yves.de.spryker.local/sitemap.xml

In multi-store context, URLs of all stores are included in the same sitemap index file.
Sitemap index file structure example when using abstract product connector:

```xml
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>http://yves.at.spryker.local/sitemap_abstract_product_at_1.xml</loc> # AT store URLs
    </sitemap>
    <sitemap>
        <loc>http://yves.de.spryker.local/sitemap_abstract_product_de_1.xml</loc> # DE store URLs
    </sitemap>
</sitemapindex>
```

However, if your stores are configured to use different databases, there will be separate sitemap index files for each different database
