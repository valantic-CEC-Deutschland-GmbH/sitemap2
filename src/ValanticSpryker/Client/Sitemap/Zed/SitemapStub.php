<?php

namespace ValanticSpryker\Client\Sitemap\Zed;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class SitemapStub extends ZedRequestStub implements SitemapStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    public function getSitemap(SitemapRequestTransfer $sitemapTransfer): SitemapFileTransfer
    {
        /**
         * @var \Generated\Shared\Transfer\SitemapFileTransfer $sitemapTransfer
         */
        $sitemapTransfer = $this->zedStub->call('/sitemap/gateway/find-sitemap-by-filename', $sitemapTransfer);

        return $sitemapTransfer;
    }
}
