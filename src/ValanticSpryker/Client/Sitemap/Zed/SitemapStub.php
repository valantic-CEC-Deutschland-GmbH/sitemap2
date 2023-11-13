<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\Sitemap\Zed;

use Generated\Shared\Transfer\SitemapRequestTransfer;
use Generated\Shared\Transfer\SitemapResponseTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class SitemapStub extends ZedRequestStub implements SitemapStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapResponseTransfer
     */
    public function getSitemap(SitemapRequestTransfer $sitemapTransfer): SitemapResponseTransfer
    {
        /**
         * @var \Generated\Shared\Transfer\SitemapFileTransfer $sitemapTransfer
         */
        $sitemapTransfer = $this->zedStub->call('/sitemap/gateway/find-sitemap-by-filename', $sitemapTransfer);

        return $sitemapTransfer;
    }
}
