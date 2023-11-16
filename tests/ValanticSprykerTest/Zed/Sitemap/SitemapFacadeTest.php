<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Client\Currency\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\SitemapRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\StoreFacade;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use ValanticSpryker\Shared\Sitemap\SitemapConstants;
use ValanticSpryker\Zed\Sitemap\Business\Supervisor\SitemapCreateSupervisor;
use ValanticSpryker\Zed\Sitemap\Business\Supervisor\SitemapCreateSupervisorInterface;
use ValanticSpryker\Zed\Sitemap\Persistence\SitemapEntityManager;
use ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepository;
use ValanticSprykerTest\Zed\Sitemap\Helper\SitemapHelperCreatorPlugin;
use ValanticSprykerTest\Zed\Sitemap\SitemapTester;

/**
 * @group Sitemap
 */
class SitemapFacadeTest extends Unit
{
    private const METHOD_CREATE_SITEMAP_CREATOR_SUPERVISOR = 'createSitemapCreatorSupervisor';
    private const METHOD_GET_CURRENT_STORE = 'getCurrentStore';
    private const TEST_SITEMAP_XML = 'test_sitemap.xml';
    private const OLD_TEST_ENTITY = 'old_test_entity';
    private const ENV_APPLICATION_STORE = 'APPLICATION_STORE';

    protected SitemapTester $tester;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->tester->deleteSitemapEntities();
    }

    /**
     * @return void
     */
    public function testCreateSitemapXmlEmpty(): void
    {
        $this->tester->mockFactoryMethod(self::METHOD_CREATE_SITEMAP_CREATOR_SUPERVISOR, $this->createSitemapCreatorSupervisor([]));
        $sut = $this->tester->getFacade();

        $sut->createSitemapXml();

        $sitemapEntities = $this->tester->getSitemapEntities();
        $this->assertCount(1, $sitemapEntities);
        $sitemapEntity = $sitemapEntities[0];

        $expectedXml = <<<EOD
        <?xml version="1.0" encoding="UTF-8"?>\n
            <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>\n
        EOD;
        $this->assertEquals($this->retrieveSitemapName(), $sitemapEntity->getName());
        $this->assertXmlStringEqualsXmlString($expectedXml, $sitemapEntity->getContent());
    }

    /**
     * @return void
     */
    public function testCreateSitemapXmlWithTestCreator(): void
    {
        $this->factoryMock = $this->tester->mockFactoryMethod(
            self::METHOD_CREATE_SITEMAP_CREATOR_SUPERVISOR,
            $this->createSitemapCreatorSupervisor([$this->createSitemapHelperCreatorPlugin()]),
        );
        $sut = $this->tester->getFacade();

        $sut->createSitemapXml();

        $sitemapEntities = $this->tester->getSitemapEntities();
        $this->assertCount(2, $sitemapEntities);
        [$mainSitemapEntity, $secondarySitemapEntity] = $this->getMainAndSecondarySitemapEntities($sitemapEntities);

        $expectedLoc = SitemapHelperCreatorPlugin::TEST_BASE_URL . '/' . SitemapHelperCreatorPlugin::TEST_NAME;
        $expectedXml = <<<EOD
        <?xml version="1.0" encoding="UTF-8"?>\n
            <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n
                <sitemap>\n
                    <loc>$expectedLoc</loc>\n
                </sitemap>\n
            </sitemapindex>\n
        EOD;
        $this->assertEquals($this->retrieveSitemapName(), $mainSitemapEntity->getName());
        $this->assertXmlStringEqualsXmlString(
            SitemapHelperCreatorPlugin::TEST_CONTENT,
            $secondarySitemapEntity->getContent(),
        );
        $this->assertXmlStringEqualsXmlString(
            $expectedXml,
            $mainSitemapEntity->getContent(),
        );
    }

    /**
     * @return void
     */
    public function testCreateSitemapXmlDeletesOldFiles(): void
    {
        $oldSitemapEntity = $this->tester->createSitemapEntity([
            'name' => self::OLD_TEST_ENTITY,
        ]);

        $this->tester->mockFactoryMethod(self::METHOD_CREATE_SITEMAP_CREATOR_SUPERVISOR, $this->createSitemapCreatorSupervisor([]));
        $sut = $this->tester->getFacade();

        $sut->createSitemapXml();

        $sitemapEntities = $this->tester->getSitemapEntities();
        $this->assertCount(1, $sitemapEntities);
        $sitemapEntity = $sitemapEntities[0];
        $this->assertEquals($this->retrieveSitemapName(), $sitemapEntity->getName());
    }

    /**
     * @return void
     */
    public function testFindSitemapByCorrectFilename(): void
    {
        $sut = $this->tester->getFacade();

        $expectedSitemapFileTransfer = $this->tester->createSitemapEntity();
        $sitemapRequestTransfer = (new SitemapRequestTransfer())
            ->setFilename($expectedSitemapFileTransfer->getName());

        $sitemapResponseTransfer = $sut->findSitemapByFilename($sitemapRequestTransfer);

        $this->assertTrue($sitemapResponseTransfer->getIsSuccessful());
        $this->assertNotNull($sitemapResponseTransfer->getSitemapFile());
        $this->assertEquals($expectedSitemapFileTransfer, $sitemapResponseTransfer->getSitemapFile());
    }

    /**
     * @return void
     */
    public function testFindSitemapByNonExistingFilename(): void
    {
        $sut = $this->tester->getFacade();

        $sitemapRequestTransfer = (new SitemapRequestTransfer())
            ->setFilename(self::TEST_SITEMAP_XML);

        $sitemapResponseTransfer = $sut->findSitemapByFilename($sitemapRequestTransfer);

        $this->assertFalse($sitemapResponseTransfer->getIsSuccessful());
        $this->assertNull($sitemapResponseTransfer->getSitemapFile());
    }

    /**
     * @return \ValanticSprykerTest\Zed\Sitemap\Helper\SitemapHelperCreatorPlugin
     */
    private function createSitemapHelperCreatorPlugin(): SitemapHelperCreatorPlugin
    {
        return new SitemapHelperCreatorPlugin();
    }

    /**
     * @param array<\Orm\Zed\Sitemap\Persistence\PyzSitemap> $sitemapEntities
     *
     * @return array<\Orm\Zed\Sitemap\Persistence\PyzSitemap>
     */
    private function getMainAndSecondarySitemapEntities(array $sitemapEntities): array
    {
        $mainSitemapEntity = null;
        $secondarySitemapEntity = null;

        foreach ($sitemapEntities as $sitemapEntity) {
            if ($sitemapEntity->getName() === SitemapConstants::SITEMAP_NAME . SitemapConstants::DOT_XML_EXTENSION) {
                $mainSitemapEntity = $sitemapEntity;

                continue;
            }

            $secondarySitemapEntity = $sitemapEntity;
        }

        return [$mainSitemapEntity, $secondarySitemapEntity];
    }

    /**
     * @param array<\ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface> $sitemapCreators
     *
     * @return \ValanticSpryker\Zed\Sitemap\Business\Supervisor\SitemapCreateSupervisorInterface
     */
    private function createSitemapCreatorSupervisor(array $sitemapCreators = []): SitemapCreateSupervisorInterface
    {
        /** @var \ValanticSpryker\Zed\Sitemap\Business\SitemapBusinessFactory $factory */
        $factory = $this->tester->getFactory();

        return new SitemapCreateSupervisor(
            $this->createStoreFacadeMock(),
            $sitemapCreators,
            new SitemapEntityManager(),
            new SitemapRepository(),
            $factory->getConfig(),
        );
    }

    /**
     * @return string
     */
    private function retrieveSitemapName(): string
    {
        return SitemapConstants::SITEMAP_NAME . SitemapConstants::DOT_XML_EXTENSION;
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface|\ValanticSprykerTest\Client\Currency\Plugin\MockObject
     */
    private function createStoreFacadeMock(): StoreFacadeInterface
    {
        $storeFacadeMock = $this->createMock(StoreFacade::class);
        $storeFacadeMock->method(self::METHOD_GET_CURRENT_STORE)
            ->willReturn($this->getCurrentStoreTransfer());

        return $storeFacadeMock;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    private function getCurrentStoreTransfer(): StoreTransfer
    {
        return (new StoreBuilder(['name' => getenv(self::ENV_APPLICATION_STORE)]))->build();
    }
}
