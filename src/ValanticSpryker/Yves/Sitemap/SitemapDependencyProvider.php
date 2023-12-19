<?php

declare(strict_types = 1);

namespace ValanticSpryker\Yves\Sitemap;

use Spryker\Client\Store\StoreClientInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use ValanticSpryker\Client\Sitemap\SitemapClientInterface;
use ValanticSpryker\Shared\Sitemap\Dependency\Plugin\SitemapResolverPluginInterface;
use ValanticSpryker\Yves\Sitemap\Plugin\Resolver\SitemapDatabaseResolverPlugin;

class SitemapDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SITEMAP = 'CLIENT_SITEMAP';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const AVAILABLE_ROUTE_RESOURCES = 'AVAILABLE_ROUTE_RESOURCES';

    /**
     * @var string
     */
    public const CLIENT_RESOLVER_PLUGIN = 'CLIENT_RESOLVER_PLUGIN';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $this->addSitemapClient($container);
        $this->addStoreClient($container);
        $this->addAvailableSitemapRouteResources($container);
        $this->addSitemapResolverPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addSitemapClient(Container $container): void
    {
        $container->set(static::CLIENT_SITEMAP, static function (Container $container): SitemapClientInterface {
            return $container->getLocator()->sitemap()->client();
        });
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addStoreClient(Container $container): void
    {
        $container->set(static::CLIENT_STORE, static function (Container $container): StoreClientInterface {
            return $container->getLocator()->store()->client();
        });
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addAvailableSitemapRouteResources(Container $container): void
    {
        $container->set(self::AVAILABLE_ROUTE_RESOURCES, $this->getAvailableSitemapRouteResources());
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addSitemapResolverPlugin(Container $container): void
    {
        $container->set(self::CLIENT_RESOLVER_PLUGIN, $this->getSitemapResolverPlugin());
    }

    /**
     * @return \ValanticSpryker\Shared\Sitemap\Dependency\Plugin\SitemapResolverPluginInterface
     */
    protected function getSitemapResolverPlugin(): SitemapResolverPluginInterface
    {
        return new SitemapDatabaseResolverPlugin(); // override with custom resolver plugin if needed
    }

    /**
     * @return array<string>
     */
    protected function getAvailableSitemapRouteResources(): array
    {
        return [
            // Here register resource types from connector modules
        ];
    }
}
