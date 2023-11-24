<?php

declare(strict_types = 1);

namespace ValanticSpryker\Yves\Sitemap;

use Spryker\Client\Store\StoreClientInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use ValanticSpryker\Client\Sitemap\SitemapClientInterface;
use ValanticSpryker\Shared\Sitemap\Dependency\Plugin\SitemapResolverPluginInterface;

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
    public const RESOURCES_SITEMAP = 'RESOURCES_SITEMAP';

    /**
     * @var string
     */
    public const RESOLVER_SITEMAP = 'RESOLVER_SITEMAP';

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
        $container->set(static::CLIENT_SITEMAP, $this->getSitemapClient($container));
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \ValanticSpryker\Client\Sitemap\SitemapClientInterface
     */
    protected function getSitemapClient(Container $container): SitemapClientInterface
    {
        return $container->getLocator()->sitemap()->client();
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addStoreClient(Container $container): void
    {
        $container->set(static::CLIENT_STORE, $this->getStoreClient($container));
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    protected function getStoreClient(Container $container): StoreClientInterface
    {
        return $container->getLocator()->store()->client();
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addAvailableSitemapRouteResources(Container $container): void
    {
        $container->set(self::RESOURCES_SITEMAP, $this->getAvailableSitemapRouteResources());
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addSitemapResolverPlugin(Container $container): void
    {
        $container->set(self::RESOLVER_SITEMAP, $this->getCustomSitemapResolverPlugin());
    }

    /**
     * @return \ValanticSpryker\Shared\Sitemap\Dependency\Plugin\SitemapResolverPluginInterface|null
     */
    protected function getCustomSitemapResolverPlugin(): ?SitemapResolverPluginInterface
    {
        return null; // include custom resolver plugin
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
