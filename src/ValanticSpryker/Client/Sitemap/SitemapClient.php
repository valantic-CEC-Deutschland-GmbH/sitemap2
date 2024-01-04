<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\Sitemap;

use Generated\Shared\Transfer\SitemapRequestTransfer;
use Generated\Shared\Transfer\SitemapResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;
use ValanticSpryker\Client\Sitemap\Zed\SitemapStubInterface;

/**
 * @method \ValanticSpryker\Client\Sitemap\SitemapFactory getFactory()
 */
class SitemapClient extends AbstractClient implements SitemapClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapResponseTransfer
     */
    public function getSitemap(SitemapRequestTransfer $sitemapTransfer): SitemapResponseTransfer
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
