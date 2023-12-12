<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Persistence;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;

interface SitemapRepositoryInterface
{
    /**
     * @param string|null $storeName
     * @param array<string> $names
     *
     * @return array<\Generated\Shared\Transfer\PyzSitemapEntityTransfer>
     */
    public function findAllSitemapsByStoreNameExceptWithGivenNames(?string $storeName, array $names): array;

    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer|null
     */
    public function findSitemapByFilename(SitemapRequestTransfer $sitemapRequestTransfer): ?SitemapFileTransfer;
}
