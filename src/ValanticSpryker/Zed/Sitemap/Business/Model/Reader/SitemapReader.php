<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Business\Model\Reader;

use Generated\Shared\Transfer\SitemapRequestTransfer;
use Generated\Shared\Transfer\SitemapResponseTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface;

class SitemapReader implements SitemapReaderInterface
{
    protected SitemapRepositoryInterface $repository;

    protected StoreFacadeInterface $storeFacade;

    /**
     * @param \ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface $repository
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     */
    public function __construct(
        SitemapRepositoryInterface $repository,
        StoreFacadeInterface $storeFacade
    ) {
        $this->repository = $repository;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapResponseTransfer
     */
    public function findSitemapByFilename(SitemapRequestTransfer $sitemapRequestTransfer): SitemapResponseTransfer
    {
        $storeName = $this->storeFacade->getCurrentStore()->getName();
        $sitemapFileTransfer = $this->repository->findSitemapByFilenameAndStore($sitemapRequestTransfer, $storeName);

        return (new SitemapResponseTransfer())
            ->setIsSuccessful($sitemapFileTransfer !== null)
            ->setSitemapFile($sitemapFileTransfer);
    }
}
