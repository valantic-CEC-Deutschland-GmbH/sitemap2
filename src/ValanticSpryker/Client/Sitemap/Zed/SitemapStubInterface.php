<?php

namespace ValanticSpryker\Client\Sitemap\Zed;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;

interface SitemapStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    public function getSitemap(SitemapRequestTransfer $sitemapTransfer): SitemapFileTransfer;
}
