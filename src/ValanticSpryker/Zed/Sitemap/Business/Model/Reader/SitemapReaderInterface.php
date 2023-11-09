<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Business\Model\Reader;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;

interface SitemapReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    public function findSitemapByFilename(SitemapRequestTransfer $sitemapRequestTransfer): SitemapFileTransfer;
}
