<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Persistence;

use Generated\Shared\Transfer\PyzSitemapEntityTransfer;
use Generated\Shared\Transfer\SitemapFileTransfer;

interface SitemapEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SitemapFileTransfer $sitemapFileTransfer
     *
     * @return \Generated\Shared\Transfer\PyzSitemapEntityTransfer
     */
    public function saveSitemapFile(SitemapFileTransfer $sitemapFileTransfer): PyzSitemapEntityTransfer;

    /**
     * @param int $id
     *
     * @return void
     */
    public function removeSitemap(int $id): void;
}
