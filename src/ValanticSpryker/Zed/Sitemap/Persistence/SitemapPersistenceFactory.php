<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Persistence;

use Orm\Zed\Sitemap\Persistence\ValSitemapQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use ValanticSpryker\Zed\Sitemap\Persistence\Mapper\SitemapMapper;

/**
 * @method \ValanticSpryker\Zed\Sitemap\SitemapConfig getConfig()
 * @method \ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface getRepository()
 * @method \ValanticSpryker\Zed\Sitemap\Persistence\SitemapEntityManagerInterface getEntityManager()
 */
class SitemapPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sitemap\Persistence\ValSitemapQuery
     */
    public function getValSitemapQuery(): ValSitemapQuery
    {
        return ValSitemapQuery::create();
    }

    /**
     * @return \ValanticSpryker\Zed\Sitemap\Persistence\Mapper\SitemapMapper
     */
    public function createSitemapMapper(): SitemapMapper
    {
        return new SitemapMapper();
    }
}
