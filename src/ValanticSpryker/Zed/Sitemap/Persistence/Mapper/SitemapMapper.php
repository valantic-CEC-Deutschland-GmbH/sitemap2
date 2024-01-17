<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Persistence\Mapper;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\ValSitemapEntityTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class SitemapMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $sitemaps
     *
     * @return array<\Generated\Shared\Transfer\ValSitemapEntityTransfer>
     */
    public function mapValSitemapCollectionToEntityTransfers(ObjectCollection $sitemaps): array
    {
        $entityTransfers = [];

        foreach ($sitemaps as $sitemap) {
            $entityTransfers[] = (new ValSitemapEntityTransfer())->fromArray($sitemap->toArray(), true);
        }

        return $entityTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $sitemaps
     *
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function mapValSitemapCollectionToSitemapFileTransfers(ObjectCollection $sitemaps): array
    {
        $entityTransfers = [];

        foreach ($sitemaps as $sitemap) {
            $entityTransfers[] = (new SitemapFileTransfer())->fromArray($sitemap->toArray(), true);
        }

        return $entityTransfers;
    }
}
