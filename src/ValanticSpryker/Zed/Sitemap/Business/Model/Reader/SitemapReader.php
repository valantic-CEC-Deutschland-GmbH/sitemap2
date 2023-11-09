<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Business\Model\Reader;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;
use ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface;

class SitemapReader implements SitemapReaderInterface
{
    protected SitemapRepositoryInterface $repository;

    /**
     * @param \ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface $repository
     */
    public function __construct(SitemapRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    public function findSitemapByFilename(SitemapRequestTransfer $sitemapRequestTransfer): SitemapFileTransfer
    {
        return $this->repository->findSitemapByFilename($sitemapRequestTransfer);
    }
}
