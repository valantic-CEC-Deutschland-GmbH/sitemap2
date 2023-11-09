<?php

declare(strict_types=1);

namespace ValanticSpryker\Zed\Sitemap\Persistence;

use Generated\Shared\Transfer\PyzSitemapEntityTransfer;
use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \ValanticSpryker\Zed\Sitemap\Persistence\SitemapPersistenceFactory getFactory()
 */
class SitemapRepository extends AbstractRepository implements SitemapRepositoryInterface
{
    /**
     * @param array<string> $names
     *
     * @return array<\Generated\Shared\Transfer\PyzSitemapEntityTransfer>
     */
    public function findAllSitemapsExceptWithGivenNames(array $names): array
    {
        $query = $this->getFactory()
            ->getPyzSitemapQuery()
            ->filterByName($names, Criteria::NOT_IN);

        return $this->mapPyzSitemapCollectionToEntityTransfers($query->find());
    }

    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    public function findSitemapByFilename(SitemapRequestTransfer $sitemapRequestTransfer): SitemapFileTransfer
    {
        $sitemapEntity = $this->getFactory()
            ->getPyzSitemapQuery()
            ->filterByName($sitemapRequestTransfer->getFilename())
            ->findOne();

        if ($sitemapEntity === null) {
            return new SitemapFileTransfer();
        }

        return (new SitemapFileTransfer())->fromArray($sitemapEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $sitemaps
     *
     * @return array<\Generated\Shared\Transfer\PyzSitemapEntityTransfer>
     */
    protected function mapPyzSitemapCollectionToEntityTransfers(ObjectCollection $sitemaps): array
    {
        $entityTransfers = [];

        foreach ($sitemaps as $sitemap) {
            $entityTransfers[] = (new PyzSitemapEntityTransfer())->fromArray($sitemap->toArray(), true);
        }

        return $entityTransfers;
    }
}
