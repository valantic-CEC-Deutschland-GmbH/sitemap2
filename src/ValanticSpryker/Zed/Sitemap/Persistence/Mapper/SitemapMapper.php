<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Persistence\Mapper;

use Generated\Shared\Transfer\PyzSitemapEntityTransfer;
use Generated\Shared\Transfer\SitemapFileTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class SitemapMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $sitemaps
     *
     * @return array<\Generated\Shared\Transfer\PyzSitemapEntityTransfer>
     */
    public function mapPyzSitemapCollectionToEntityTransfers(ObjectCollection $sitemaps): array
    {
        $entityTransfers = [];

        foreach ($sitemaps as $sitemap) {
            $entityTransfers[] = (new PyzSitemapEntityTransfer())->fromArray($sitemap->toArray(), true);
        }

        return $entityTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $sitemaps
     *
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function mapPyzSitemapCollectionToSitemapFileTransfers(ObjectCollection $sitemaps): array
    {
        $entityTransfers = [];

        foreach ($sitemaps as $sitemap) {
            $entityTransfers[] = (new SitemapFileTransfer())->fromArray($sitemap->toArray(), true);
        }

        return $entityTransfers;
    }
}
