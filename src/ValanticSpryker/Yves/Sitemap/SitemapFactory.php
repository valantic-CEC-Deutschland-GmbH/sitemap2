<?php

declare(strict_types = 1);

namespace ValanticSpryker\Yves\Sitemap;

use Spryker\Client\Store\StoreClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use ValanticSpryker\Client\Sitemap\SitemapClientInterface;

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
}
