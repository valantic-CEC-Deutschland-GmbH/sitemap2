<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Client\Currency\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\PyzSitemapEntityTransfer;
use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\StoreFacade;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use ValanticSpryker\Shared\Sitemap\SitemapConstants;
use ValanticSpryker\Zed\Sitemap\Business\Model\Reader\SitemapReader;
use ValanticSpryker\Zed\Sitemap\Business\SitemapFacadeInterface;
use ValanticSpryker\Zed\Sitemap\Business\Supervisor\SitemapCreateSupervisor;
use ValanticSpryker\Zed\Sitemap\Business\Supervisor\SitemapCreateSupervisorInterface;
use ValanticSpryker\Zed\Sitemap\Persistence\SitemapEntityManager;
use ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepository;
use ValanticSprykerTest\Zed\Sitemap\Helper\SitemapHelperCreatorPlugin;
use ValanticSprykerTest\Zed\Sitemap\SitemapTester;

/**
 * @group SitemapDb
 */
class SitemapFacadeTest extends Unit
{
    private const METHOD_CREATE_SITEMAP_CREATOR_SUPERVISOR = 'createSitemapCreatorSupervisor';
    private const METHOD_GET_CURRENT_STORE = 'getCurrentStore';
    private const METHOD_CREATE_SITEMAP_READER = 'createSitemapReader';
    private const METHOD_FIND_ALL_SITEMAPS_BY_STORE_NAME_EXCEPT_WITH_GIVEN_NAMES = 'findAllSitemapsByStoreNameExceptWithGivenNames';
    private const METHOD_SAVE_SITEMAP_FILE = 'saveSitemapFile';
    private const METHOD_REMOVE_SITEMAP = 'removeSitemap';
    private const METHOD_FIND_SITEMAP_BY_FILENAME = 'findSitemapByFilename';
    private const TEST_SITEMAP_XML = 'test_sitemap.xml';
    private const ENV_APPLICATION_STORE = 'APPLICATION_STORE';
    private const ID_SITEMAP_TO_REMOVE = 1;
    private const STORE_NAME_DE = 'DE';
    private const XML_EMPTY_SITEMAP = <<<EOD
        <?xml version="1.0" encoding="UTF-8"?>
        <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>\n
        EOD;

    protected SitemapTester $tester;

    /**
     * @var \ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepository|\PHPUnit\Framework\MockObject\MockObject
     */
    private SitemapRepository $repositoryMock;

    /**
     * @var \ValanticSpryker\Zed\Sitemap\Persistence\SitemapEntityManager|\PHPUnit\Framework\MockObject\MockObject
     */
    private SitemapEntityManager $entityManagerMock;

    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private StoreFacadeInterface $storeFacadeMock;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->repositoryMock = $this->createMock(SitemapRepository::class);
        $this->entityManagerMock = $this->createMock(SitemapEntityManager::class);
        $this->storeFacadeMock = $this->createStoreFacadeMock();
    }

    /**
     * @return void
     */
    public function testCreateSitemapXmlEmpty(): void
    {
        $this->tester->mockFactoryMethod(self::METHOD_CREATE_SITEMAP_CREATOR_SUPERVISOR, $this->createSitemapCreatorSupervisor([]));
        $sut = $this->tester->getFacade();

        $this->repositoryMock->expects($this->exactly(2))
            ->method(self::METHOD_FIND_ALL_SITEMAPS_BY_STORE_NAME_EXCEPT_WITH_GIVEN_NAMES)
            ->withConsecutive(
                [self::STORE_NAME_DE, []],
                [null, [$this->retrieveSitemapName()]],
            )
            ->willReturnOnConsecutiveCalls(
                [],
                [],
            );

        $expectedXml = self::XML_EMPTY_SITEMAP;

        $expectedSitemapFileTransfer = (new SitemapFileTransfer())
            ->setContent($expectedXml)
            ->setName($this->retrieveSitemapName());

        $this->entityManagerMock->expects($this->once())
            ->method(self::METHOD_SAVE_SITEMAP_FILE)
            ->with($expectedSitemapFileTransfer)
            ->willReturn(new PyzSitemapEntityTransfer());

        $sut->createSitemapXml();
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
        $sut = $this->setFacadeToSut();

        $expectedLoc = SitemapHelperCreatorPlugin::TEST_BASE_URL . '/' . SitemapHelperCreatorPlugin::TEST_NAME;
        $expectedXml = SitemapHelperCreatorPlugin::TEST_CONTENT;
        $expectedXml2 = <<<EOD
        <?xml version="1.0" encoding="UTF-8"?>
        <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
          <sitemap>
            <loc>$expectedLoc</loc>
          </sitemap>
        </sitemapindex>\n
        EOD;

        $expectedSitemapFileTransfer = (new SitemapFileTransfer())
            ->setContent($expectedXml)
            ->setName(SitemapHelperCreatorPlugin::TEST_NAME)
            ->setStoreName(self::STORE_NAME_DE)
            ->setYvesBaseUrl(SitemapHelperCreatorPlugin::TEST_BASE_URL);
        $expectedSitemapFileTransfer2 = (new SitemapFileTransfer())
            ->setContent($expectedXml2)
            ->setName($this->retrieveSitemapName());

        $this->entityManagerMock->expects($this->exactly(2))
            ->method(self::METHOD_SAVE_SITEMAP_FILE)
            ->withConsecutive([$expectedSitemapFileTransfer], [$expectedSitemapFileTransfer2])
            ->willReturnOnConsecutiveCalls(new PyzSitemapEntityTransfer(), new PyzSitemapEntityTransfer());

        $this->repositoryMock->expects($this->exactly(2))
            ->method(self::METHOD_FIND_ALL_SITEMAPS_BY_STORE_NAME_EXCEPT_WITH_GIVEN_NAMES)
            ->withConsecutive(
                [self::STORE_NAME_DE, [SitemapHelperCreatorPlugin::TEST_NAME]],
                [null, [$this->retrieveSitemapName()]],
            )
            ->willReturnOnConsecutiveCalls(
                [],
                [$expectedSitemapFileTransfer],
            );

        $sut->createSitemapXml();
    }

    /**
     * @return void
     */
    public function testCreateSitemapXmlDeletesOldFiles(): void
    {
        $this->tester->mockFactoryMethod(self::METHOD_CREATE_SITEMAP_CREATOR_SUPERVISOR, $this->createSitemapCreatorSupervisor([]));

        $sut = $this->setFacadeToSut();

        $expectedXml = self::XML_EMPTY_SITEMAP;

        $expectedSitemapFileTransfer = (new SitemapFileTransfer())
            ->setContent($expectedXml)
            ->setName($this->retrieveSitemapName());
        $sitemapFileTransferToRemove = clone $expectedSitemapFileTransfer;
        $sitemapFileTransferToRemove->setIdSitemap(self::ID_SITEMAP_TO_REMOVE);

        $this->repositoryMock->expects($this->exactly(2))
            ->method(self::METHOD_FIND_ALL_SITEMAPS_BY_STORE_NAME_EXCEPT_WITH_GIVEN_NAMES)
            ->withConsecutive(
                [self::STORE_NAME_DE, []],
                [null, [$this->retrieveSitemapName()]],
            )
            ->willReturnOnConsecutiveCalls(
                [$sitemapFileTransferToRemove],
                [],
            );

        $this->entityManagerMock->expects($this->once())
            ->method(self::METHOD_SAVE_SITEMAP_FILE)
            ->with($expectedSitemapFileTransfer)
            ->willReturn(new PyzSitemapEntityTransfer());

        $this->entityManagerMock->expects($this->once())
            ->method(self::METHOD_REMOVE_SITEMAP)
            ->with(self::ID_SITEMAP_TO_REMOVE);

        $sut->createSitemapXml();
    }

    /**
     * @return void
     */
    public function testFindSitemapByCorrectFilename(): void
    {
        $this->tester->mockFactoryMethod(self::METHOD_CREATE_SITEMAP_READER, $this->createSitemapReader());

        $sut = $this->setFacadeToSut();

        $expectedSitemapFileTransfer = (new SitemapFileTransfer())
            ->setContent('test-content')
            ->setName($this->retrieveSitemapName());
        $sitemapRequestTransfer = (new SitemapRequestTransfer())
            ->setFilename($expectedSitemapFileTransfer->getName());

        $this->repositoryMock->expects($this->once())
            ->method(self::METHOD_FIND_SITEMAP_BY_FILENAME)
            ->with($sitemapRequestTransfer)
            ->willReturn($expectedSitemapFileTransfer);

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
        $this->tester->mockFactoryMethod(self::METHOD_CREATE_SITEMAP_READER, $this->createSitemapReader());

        $sut = $this->setFacadeToSut();

        $sitemapRequestTransfer = (new SitemapRequestTransfer())
            ->setFilename(self::TEST_SITEMAP_XML);

        $this->repositoryMock->expects($this->once())
            ->method(self::METHOD_FIND_SITEMAP_BY_FILENAME)
            ->with($sitemapRequestTransfer)
            ->willReturn(null);

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
     * @param array<\ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface> $sitemapCreators
     *
     * @return \ValanticSpryker\Zed\Sitemap\Business\Supervisor\SitemapCreateSupervisorInterface
     */
    private function createSitemapCreatorSupervisor(array $sitemapCreators = []): SitemapCreateSupervisorInterface
    {
        return new SitemapCreateSupervisor(
            $this->storeFacadeMock,
            $sitemapCreators,
            $this->entityManagerMock,
            $this->repositoryMock,
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

    /**
     * @return \ValanticSpryker\Zed\Sitemap\Business\SitemapFacadeInterface
     */
    private function setFacadeToSut(): SitemapFacadeInterface
    {
        $sut = $this->tester->getFacade();
        $sut->setEntityManager($this->entityManagerMock);
        $sut->setRepository($this->repositoryMock);

        return $sut;
    }

    /**
     * @return \ValanticSpryker\Zed\Sitemap\Business\Model\Reader\SitemapReader
     */
    private function createSitemapReader(): SitemapReader
    {
        return new SitemapReader($this->repositoryMock);
    }
}
