<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Service\Sitemap;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\SitemapUrlBuilder;
use Generated\Shared\Transfer\SitemapUrlTransfer;
use ValanticSpryker\Service\Sitemap\SitemapConfig;
use ValanticSpryker\Service\Sitemap\SitemapService;
use ValanticSpryker\Service\Sitemap\SitemapServiceInterface;

/**
 * @group Sitemap
 */
class SitemapServiceTest extends Unit
{
    protected SitemapServiceTester $tester;

    private SitemapServiceInterface $sut;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->tester->setConfig('APPLICATION:BASE_URL_YVES', 'test-base.url');
        $this->tester->setConfig('APPLICATION_ENV', 'test');
        $this->sut = new SitemapService();
    }

    /**
     * @return void
     */
    public function testCreateSitemapXmlFileTransferGeneratesValidFileTransfer(): void
    {
        $sitemapUrl = $this->generateSitemapUrl();
        $urlList = [$sitemapUrl];
        $page = 1;
        $storeName = getenv('APPLICATION_STORE');
        $fileType = 'testType';

        $sitemapFileTransfer = $this->sut->createSitemapXmlFileTransfer($urlList, $page, $storeName, $fileType);

        $expectedLoc = $sitemapUrl->getUrl();
        $expectedDate = (new DateTime($sitemapUrl->getUpdatedAt()))->format(SitemapConfig::LAST_MOD_FORMAT);
        $expectedXmlString = <<<EOD
        <?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
              <url>
                <loc>$expectedLoc</loc>
                <lastmod>$expectedDate</lastmod>
              </url>
            </urlset>
        EOD;

        $this->assertNotNull($sitemapFileTransfer);
        $this->assertEquals($storeName, $sitemapFileTransfer->getStoreName());
        $this->assertEquals('sitemap_testType_de_1.xml', $sitemapFileTransfer->getName());
        $this->assertXmlStringEqualsXmlString($expectedXmlString, $sitemapFileTransfer->getContent());
    }

    /**
     * @return void
     */
    public function testCreateSitemapXmlFileTransferGeneratesNullWithEmptyList(): void
    {
        $urlList = [];
        $page = 1;
        $storeName = getenv('APPLICATION_STORE');
        $fileType = 'testType';

        $sitemapFileTransfer = $this->sut->createSitemapXmlFileTransfer($urlList, $page, $storeName, $fileType);

        $this->assertNull($sitemapFileTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\SitemapUrlTransfer
     */
    private function generateSitemapUrl(): SitemapUrlTransfer
    {
        return (new SitemapUrlBuilder())->build();
    }
}
