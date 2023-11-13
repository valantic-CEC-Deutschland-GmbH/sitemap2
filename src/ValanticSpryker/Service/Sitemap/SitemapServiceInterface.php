<?php

declare(strict_types = 1);

namespace ValanticSpryker\Service\Sitemap;

use Generated\Shared\Transfer\SitemapFileTransfer;

interface SitemapServiceInterface
{
    /**
     * Creates sitemap xml file transfer
     *
     * @api
     *
     * @param array $urlList
     * @param int $page
     * @param string $storeName
     * @param string $fileType
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer|null
     */
    public function createSitemapXmlFileTransfer(
        array $urlList,
        int $page,
        string $storeName,
        string $fileType
    ): ?SitemapFileTransfer;
}
