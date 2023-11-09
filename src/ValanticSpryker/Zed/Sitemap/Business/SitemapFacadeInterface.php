<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Business;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;

interface SitemapFacadeInterface
{
    /**
     * Specification:
     * - creates sitemap XML files and stores them to database
     *
     * @return void
     */
    public function createSitemapXml(): void;

    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    public function findSitemapByFilename(SitemapRequestTransfer $sitemapRequestTransfer): SitemapFileTransfer;
}
