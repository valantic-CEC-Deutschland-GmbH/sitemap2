# Sitemap:

## Integration

### Add composer registry
```
composer config repositories.gitlab.nxs360.com/461 '{"type": "composer", "url": "https://gitlab.nxs360.com/api/v4/group/461/-/packages/composer/packages.json"}'
```

### Add Gitlab domain
```
composer config gitlab-domains gitlab.nxs360.com
```

### Authentication
Go to Gitlab and create a personal access token. Then create an **auth.json** file:
```
composer config gitlab-token.gitlab.nxs360.com <personal_access_token>
```

Make sure to add **auth.json** to your **.gitignore**.

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

7. Register connector modules to see resources such as category, product abstract urls. Information is provided in each connector module:
   1. https://gitlab.nxs360.com/packages/php/spryker/category-sitemap-connector
   2. https://gitlab.nxs360.com/packages/php/spryker/content-pages-sitemap-connector
   3. https://gitlab.nxs360.com/packages/php/spryker/product-abstract-sitemap-connector

8. If you want to retrieve sitemap data from Redis instead of DB, install `sitemap-storage` module:
   1. https://gitlab.nxs360.com/packages/php/spryker/sitemap-storage

## Access Sitemap

The index of sitemap is `/sitemap.xml`, so for example on demo shop that would be http://yves.de.spryker.local/sitemap.xml

Each store has different sitemap, for example:

http://yves.at.spryker.local/sitemap.xml -> AT store
http://yves.de.spryker.local/sitemap.xml -> DE store
