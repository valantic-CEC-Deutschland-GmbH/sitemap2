<?php

declare(strict_types = 1);

namespace ValanticSpryker\Yves\Sitemap;

use Spryker\Client\Store\StoreClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use ValanticSpryker\Client\Sitemap\SitemapClientInterface;
use ValanticSpryker\Shared\Sitemap\Dependency\Plugin\SitemapResolverPluginInterface;
use ValanticSpryker\Yves\Sitemap\Plugin\Resolver\SitemapDatabaseResolverPlugin;

class SitemapFactory extends AbstractFactory
{
    /**
     * @return \ValanticSpryker\Client\Sitemap\SitemapClientInterface
     */
    public function getSitemapClient(): SitemapClientInterface
    {
        return $this->getProvidedDependency(SitemapDependencyProvider::CLIENT_SITEMAP);
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(SitemapDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return array<string>
     */
    public function getAvailableResourceTypes(): array
    {
        return $this->getProvidedDependency(SitemapDependencyProvider::RESOURCES_SITEMAP);
    }

    /**
     * @return \ValanticSpryker\Shared\Sitemap\Dependency\Plugin\SitemapResolverPluginInterface
     */
    public function getSitemapResolverPlugin(): SitemapResolverPluginInterface
    {
        $resolverDependency = $this->getProvidedDependency(SitemapDependencyProvider::RESOLVER_SITEMAP);

        if ($resolverDependency instanceof SitemapResolverPluginInterface) {
            return $resolverDependency;
        }

        return $this->createSitemapDatabaseResolver();
    }

    /**
     * @return \ValanticSpryker\Shared\Sitemap\Dependency\Plugin\SitemapResolverPluginInterface
     */
    private function createSitemapDatabaseResolver(): SitemapResolverPluginInterface
    {
        return new SitemapDatabaseResolverPlugin();
    }
}
