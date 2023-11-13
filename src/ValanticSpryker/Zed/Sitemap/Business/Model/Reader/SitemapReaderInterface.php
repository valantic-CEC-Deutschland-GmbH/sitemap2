<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Business\Model\Reader;

use Generated\Shared\Transfer\SitemapRequestTransfer;
use Generated\Shared\Transfer\SitemapResponseTransfer;

interface SitemapReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapResponseTransfer
     */
    public function findSitemapByFilename(SitemapRequestTransfer $sitemapRequestTransfer): SitemapResponseTransfer;
}
