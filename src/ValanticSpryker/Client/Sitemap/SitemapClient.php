<?php

namespace ValanticSpryker\Client\Sitemap;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;
use ValanticSpryker\Client\Sitemap\Zed\SitemapStubInterface;

/**
 * @method \ValanticSpryker\Client\Sitemap\SitemapFactory getFactory()
 */
class SitemapClient extends AbstractClient implements SitemapClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\SitemapTransfer $sitemapTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    public function getSitemap(SitemapRequestTransfer $sitemapTransfer): SitemapFileTransfer
    {
        return $this->getZedStub()->getSitemap($sitemapTransfer);
    }

    /**
     * @return \ValanticSpryker\Client\Sitemap\Zed\SitemapStubInterface
     */
    protected function getZedStub(): SitemapStubInterface
    {
        return $this->getFactory()->createZedStub();
    }
}
