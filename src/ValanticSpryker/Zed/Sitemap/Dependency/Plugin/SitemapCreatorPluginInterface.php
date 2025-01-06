<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Dependency\Plugin;

interface SitemapCreatorPluginInterface
{
    /**
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function createSitemapXml(string $storeName): array;
}
