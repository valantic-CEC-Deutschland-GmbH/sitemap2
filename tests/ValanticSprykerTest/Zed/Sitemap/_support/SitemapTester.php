<?php

declare(strict_types=1);

namespace ValanticSprykerTest\Zed\Sitemap;


use Generated\Shared\DataBuilder\SitemapFileBuilder;
use Generated\Shared\Transfer\SitemapFileTransfer;
use Orm\Zed\Sitemap\Persistence\PyzSitemapQuery;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class SitemapTester extends \Codeception\Actor
{
    use _generated\SitemapTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @return void
     */
    public function deleteSitemapEntities(): void
    {
        (PyzSitemapQuery::create())
            ->filterByName_Like("%%")
            ->delete();
    }

    /**
     * @return array<Orm\Zed\Sitemap\Persistence\PyzSitemap>
     */
    public function getSitemapEntities(): array
    {
        return (PyzSitemapQuery::create())
            ->find()
            ->getData();
    }

    /**
     * @return SitemapFileTransfer
     */
    public function createSitemapEntity(array $alias = []): SitemapFileTransfer
    {
        $sitemapFileTransfer = (new SitemapFileBuilder($alias))->build();
        $sitemapFileTransfer->setStoreName(getenv('APPLICATION_STORE'));

        $sitemapEntity = (PyzSitemapQuery::create())
            ->filterByName($sitemapFileTransfer->getName())
            ->filterByContent($sitemapFileTransfer->getContent())
            ->filterByStoreName($sitemapFileTransfer->getStoreName())
            ->filterByYvesBaseUrl($sitemapFileTransfer->getYvesBaseUrl())
            ->findOneOrCreate();

        $sitemapEntity->save();

        $sitemapFileTransfer->setIdSitemap($sitemapEntity->getIdSitemap());

        return $sitemapFileTransfer;
    }
}
