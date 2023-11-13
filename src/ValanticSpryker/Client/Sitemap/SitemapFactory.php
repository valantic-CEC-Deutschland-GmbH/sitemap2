<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\Sitemap;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use ValanticSpryker\Client\Sitemap\Zed\SitemapStub;
use ValanticSpryker\Client\Sitemap\Zed\SitemapStubInterface;

class SitemapFactory extends AbstractFactory
{
    /**
     * @return \ValanticSpryker\Client\Sitemap\Zed\SitemapStubInterface
     */
    public function createZedStub(): SitemapStubInterface
    {
        return new SitemapStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(SitemapDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
