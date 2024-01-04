<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Communication\Controller;

use Generated\Shared\Transfer\SitemapRequestTransfer;
use Generated\Shared\Transfer\SitemapResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \ValanticSpryker\Zed\Sitemap\Business\SitemapFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapResponseTransfer
     */
    public function findSitemapByFilenameAction(SitemapRequestTransfer $sitemapRequestTransfer): SitemapResponseTransfer
    {
        return $this->getFacade()
            ->findSitemapByFilename($sitemapRequestTransfer);
    }
}
