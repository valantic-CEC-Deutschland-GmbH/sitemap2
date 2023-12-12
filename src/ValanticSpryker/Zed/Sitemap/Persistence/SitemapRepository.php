<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Persistence;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \ValanticSpryker\Zed\Sitemap\Persistence\SitemapPersistenceFactory getFactory()
 */
class SitemapRepository extends AbstractRepository implements SitemapRepositoryInterface
{
    /**
     * @param string|null $storeName
     * @param array<string> $names
     *
     * @return array<\Generated\Shared\Transfer\PyzSitemapEntityTransfer>
     */
    public function findAllSitemapsByStoreNameExceptWithGivenNames(?string $storeName, array $names): array
    {
        $query = $this->getFactory()
            ->getPyzSitemapQuery()
            ->filterByName($names, Criteria::NOT_IN);

        if ($storeName) {
            $query->filterByStoreName($storeName);
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $resultCollection */
        $resultCollection = $query->find();

        return $this->getFactory()
            ->createSitemapMapper()
            ->mapPyzSitemapCollectionToEntityTransfers($resultCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer|null
     */
    public function findSitemapByFilename(SitemapRequestTransfer $sitemapRequestTransfer): ?SitemapFileTransfer
    {
        $sitemapEntity = $this->getFactory()
            ->getPyzSitemapQuery()
            ->filterByName($sitemapRequestTransfer->getFilename())
            ->findOne();

        if (!$sitemapEntity) {
            return null;
        }

        return (new SitemapFileTransfer())->fromArray($sitemapEntity->toArray(), true);
    }
}
