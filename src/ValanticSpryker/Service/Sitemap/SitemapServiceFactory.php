<?php

declare(strict_types = 1);

namespace ValanticSpryker\Service\Sitemap;

use Spryker\Service\Kernel\AbstractServiceFactory;
use ValanticSpryker\Service\Sitemap\Creator\SitemapXmlFileTransferCreator;

/**
 * @method \ValanticSpryker\Service\Sitemap\SitemapConfig getConfig()
 */
class SitemapServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \ValanticSpryker\Service\Sitemap\Creator\SitemapXmlFileTransferCreator
     */
    public function createSitemapXmlFileTransferCreator(): SitemapXmlFileTransferCreator
    {
        return new SitemapXmlFileTransferCreator(
            $this->getConfig(),
        );
    }
}
