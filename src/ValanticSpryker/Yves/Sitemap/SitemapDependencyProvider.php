<?php

namespace ValanticSpryker\Yves\Sitemap;

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
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \ValanticSpryker\Client\Sitemap\SitemapClientInterface|\Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addSitemapClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSitemapClient(Container $container): Container
    {
        $container->set(static::CLIENT_SITEMAP, $this->getSitemapClient($container));

        return $container;
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
}
