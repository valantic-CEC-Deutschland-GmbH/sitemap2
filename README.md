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

## Implementation

1. Install dependency
```
composer require valantic-spryker/sitemap
```

2. Register RouterPlugin
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

5. Replace project name
- Add cronjob in current/config/Zed/cronjobs/jenkins.php
```php
$jobs[] = [
    'name' => 'generate-sitemap',
    'command' => '$PHP_BIN vendor/bin/console sitemap:generate -vvv',
    'schedule' => '0 0 1 1 *',
    'enable' => false,
    'run_on_non_production' => true,
    'stores' => $allStores,
];
```

6. Adjust config file
- Add sitemap constants
```php
$config[SitemapConstants::SITEMAP_URL_LIMIT] = 50;
```

7. Copy vendor template files into project folder
```
mkdir -p src/Pyz/Zed/Sitemap/Presentation
cp -r vendor/valantic-spryker/sitemap/src/ValanticSpryker/Zed/Sitemap/Presentation/* src/Pyz/Zed/Sitemap/Presentation
```

## Access Sitemap
The following paths are considered
```
  - sitemap_products_{storeName}_{number}.xml
  - sitemap_categories_{storeName}_{number}.xml
  - sitemap_cms_{storeName}_{number}.xml
  - sitemap_{number}.xml
  - sitemap.xml
```
