<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Persistence;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\ValSitemapEntityTransfer;

interface SitemapEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SitemapFileTransfer $sitemapFileTransfer
     *
     * @return \Generated\Shared\Transfer\ValSitemapEntityTransfer
     */
    public function saveSitemapFile(SitemapFileTransfer $sitemapFileTransfer): ValSitemapEntityTransfer;

    /**
     * @param int $id
     *
     * @return void
     */
    public function removeSitemap(int $id): void;
}
