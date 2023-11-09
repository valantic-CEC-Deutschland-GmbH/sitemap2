<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Communication\Controller;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \ValanticSpryker\Zed\Sitemap\Business\SitemapFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    public function findSitemapByFilenameAction(SitemapRequestTransfer $sitemapRequestTransfer): SitemapFileTransfer
    {
        return $this->getFacade()
            ->findSitemapByFilename($sitemapRequestTransfer);
    }
}
