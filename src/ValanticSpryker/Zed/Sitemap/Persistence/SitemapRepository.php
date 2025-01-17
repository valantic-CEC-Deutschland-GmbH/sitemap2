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
     * @param array<string> $namesExcluded
     *
     * @return array<\Generated\Shared\Transfer\ValSitemapEntityTransfer>
     */
    public function findAllSitemapsByStoreNameExceptWithGivenNames(?string $storeName, array $namesExcluded): array
    {
        $query = $this->getFactory()
            ->getValSitemapQuery()
            ->filterByName($namesExcluded, Criteria::NOT_IN);

        if ($storeName) {
            $query->filterByStoreName($storeName);
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $resultCollection */
        $resultCollection = $query->find();

        return $this->getFactory()
            ->createSitemapMapper()
            ->mapValSitemapCollectionToEntityTransfers($resultCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer|null
     */
    public function findSitemapByFilename(SitemapRequestTransfer $sitemapRequestTransfer): ?SitemapFileTransfer
    {
        $sitemapQuery = $this->getFactory()
            ->getValSitemapQuery()
            ->filterByName($sitemapRequestTransfer->getFilename());

        $sitemapEntity = $sitemapQuery->findOne();

        if (!$sitemapEntity) {
            return null;
        }

        return (new SitemapFileTransfer())->fromArray($sitemapEntity->toArray(), true);
    }
}
