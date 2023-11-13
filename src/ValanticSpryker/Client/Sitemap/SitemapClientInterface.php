<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\Sitemap;

use Generated\Shared\Transfer\SitemapRequestTransfer;
use Generated\Shared\Transfer\SitemapResponseTransfer;

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
     * @return \Generated\Shared\Transfer\SitemapResponseTransfer
     */
    public function getSitemap(SitemapRequestTransfer $sitemapTransfer): SitemapResponseTransfer;
}
