<?php

namespace ValanticSpryker\Client\Sitemap;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;

interface SitemapClientInterface
{
    /**
     * Specification:
     * - Returns the sitemapindex's or the sitemap's content as a string.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    public function getSitemap(SitemapRequestTransfer $sitemapTransfer): SitemapFileTransfer;
}
