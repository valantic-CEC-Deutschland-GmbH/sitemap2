<?php

declare(strict_types=1);

namespace ValanticSpryker\Zed\Sitemap\Persistence;

use Orm\Zed\Sitemap\Persistence\PyzSitemapQuery;
use Orm\Zed\UrlStorage\Persistence\SpyUrlStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \ValanticSpryker\Zed\Sitemap\SitemapConfig getConfig()
 * @method \ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface getRepository()
 * @method \ValanticSpryker\Zed\Sitemap\Persistence\SitemapEntityManagerInterface getEntityManager()
 */
class SitemapPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\UrlStorage\Persistence\SpyUrlStorageQuery
     */
    public function getSpyUrlStorageQuery(): SpyUrlStorageQuery
    {
        return SpyUrlStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\Sitemap\Persistence\PyzSitemapQuery
     */
    public function getPyzSitemapQuery(): PyzSitemapQuery
    {
        return PyzSitemapQuery::create();
    }
}
