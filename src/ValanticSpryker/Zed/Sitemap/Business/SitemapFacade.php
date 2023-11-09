<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Business;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface getRepository()
 * @method \ValanticSpryker\Zed\Sitemap\Business\SitemapBusinessFactory getFactory()
 */
class SitemapFacade extends AbstractFacade implements SitemapFacadeInterface
{
    /**
     * @return void
     */
    public function createSitemapXml(): void
    {
        $this->getFactory()->createSitemapCreatorSupervisor()->create();
    }

    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    public function findSitemapByFilename(SitemapRequestTransfer $sitemapRequestTransfer): SitemapFileTransfer
    {
        return $this->getFactory()
            ->createSitemapReader()
            ->findSitemapByFilename($sitemapRequestTransfer);
    }
}
