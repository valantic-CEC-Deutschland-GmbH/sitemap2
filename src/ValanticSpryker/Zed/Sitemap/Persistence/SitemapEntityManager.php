<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Persistence;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\ValSitemapEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \ValanticSpryker\Zed\Sitemap\Persistence\SitemapPersistenceFactory getFactory()
 * @method \ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface getRepository()
 * @method \ValanticSpryker\Zed\Sitemap\Persistence\SitemapEntityManagerInterface getEntityManager()
 */
class SitemapEntityManager extends AbstractEntityManager implements SitemapEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SitemapFileTransfer $sitemapFileTransfer
     *
     * @return \Generated\Shared\Transfer\ValSitemapEntityTransfer
     */
    public function saveSitemapFile(SitemapFileTransfer $sitemapFileTransfer): ValSitemapEntityTransfer
    {
        $sitemapEntity = $this->getFactory()->getValSitemapQuery()
            ->filterByName($sitemapFileTransfer->getName())
            ->filterByStoreName($sitemapFileTransfer->getStoreName())
            ->findOneOrCreate();

        $sitemapEntity->setContent($sitemapFileTransfer->getContent() ?? '');
        $sitemapEntity->setYvesBaseUrl($sitemapFileTransfer->getYvesBaseUrl());
        $sitemapEntity->save();

        $sitemapEntityTransfer = new ValSitemapEntityTransfer();
        $sitemapEntityTransfer->fromArray($sitemapEntity->toArray(), true);

        return $sitemapEntityTransfer;
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function removeSitemap(int $id): void
    {
        $sitemap = $this->getFactory()->getValSitemapQuery()->findOneByIdSitemap($id);

        if ($sitemap) {
            $sitemap->delete();
        }
    }
}
