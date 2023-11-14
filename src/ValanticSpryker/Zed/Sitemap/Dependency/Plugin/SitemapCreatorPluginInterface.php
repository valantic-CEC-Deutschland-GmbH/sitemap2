<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Dependency\Plugin;

interface SitemapCreatorPluginInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function createSitemapXml(): array;
}
