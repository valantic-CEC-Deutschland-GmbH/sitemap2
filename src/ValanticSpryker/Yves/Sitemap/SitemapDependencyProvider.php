<?php

declare(strict_types = 1);

namespace ValanticSpryker\Yves\Sitemap;

use Spryker\Client\Store\StoreClientInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use ValanticSpryker\Client\Sitemap\SitemapClient;

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
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \ValanticSpryker\Client\Sitemap\SitemapClientInterface|\Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $this->addSitemapClient($container);
        $this->addStoreClient($container);

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
     * @return \ValanticSpryker\Client\Sitemap\SitemapClient
     */
    protected function getSitemapClient(Container $container): SitemapClient
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
}
