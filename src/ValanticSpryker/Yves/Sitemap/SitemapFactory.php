<?php

declare(strict_types = 1);

namespace ValanticSpryker\Yves\Sitemap;

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
}
