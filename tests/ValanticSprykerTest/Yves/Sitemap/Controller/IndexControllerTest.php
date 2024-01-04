<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Yves\Sitemap\Controller;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\SitemapFileBuilder;
use Generated\Shared\Transfer\SitemapResponseTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ValanticSpryker\Client\Sitemap\SitemapClientInterface;
use ValanticSpryker\Shared\Sitemap\Dependency\Plugin\SitemapResolverPluginInterface;
use ValanticSpryker\Yves\Sitemap\Controller\IndexController;
use ValanticSpryker\Yves\Sitemap\SitemapDependencyProvider;
use ValanticSprykerTest\Yves\Sitemap\SitemapYvesTester;

/**
 * @group Sitemap
 */
class IndexControllerTest extends Unit
{
    private const FILENAME_SITEMAP_XML = '/sitemap.xml';
    private const METHOD_GET_SITEMAP = 'getSitemap';
    private const METHOD_GET_FACTORY = 'getFactory';
    private const METHOD_GET_SITEMAP_RESOLVER_PLUGIN = 'getSitemapResolverPlugin';
    private const XML_CONTENT_TYPE = 'application/xml';

    protected SitemapYvesTester $tester;

    /**
     * @var \ValanticSpryker\Yves\Sitemap\Controller\IndexController|\PHPUnit\Framework\MockObject\MockObject
     */
    private IndexController $sut;

    /**
     * @var \ValanticSpryker\Client\Sitemap\SitemapClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private SitemapClientInterface $sitemapClientMock;

    /**
     * @return void
     */
    protected function _setup(): void
    {
        parent::_setup();

        $this->sitemapClientMock = $this->createMock(SitemapClientInterface::class);
        $this->tester->setDependency(SitemapDependencyProvider::CLIENT_SITEMAP, $this->sitemapClientMock);

        $this->sut = $this->createPartialMock(IndexController::class, [self::METHOD_GET_FACTORY]);
    }

    /**
     * @return void
     */
    public function testIndexActionThrowsExceptionIfFileNotFound(): void
    {
        $request = Request::create(self::FILENAME_SITEMAP_XML);
        $this->mockSitemapDatabaseResolver();
        $this->expectException(NotFoundHttpException::class);

        $this->sut->indexAction($request);
    }

    /**
     * @return void
     */
    public function testIndexActionOutputsXml(): void
    {
        $request = Request::create(self::FILENAME_SITEMAP_XML);
        $sitemapFile = (new SitemapFileBuilder())->build();
        $responseTransfer = (new SitemapResponseTransfer())
            ->setIsSuccessful(true)
            ->setSitemapFile($sitemapFile);

        $sitemapDatabaseResolverMock = $this->mockSitemapDatabaseResolver();
        $sitemapDatabaseResolverMock->expects($this->once())
            ->method(self::METHOD_GET_SITEMAP)
            ->willReturn($responseTransfer);

        $response = $this->sut->indexAction($request);

        $this->assertEquals(self::XML_CONTENT_TYPE, $response->headers->get('Content-Type'));
        $this->assertEquals($sitemapFile->getContent(), $response->getContent());
    }

    /**
     * @return \ValanticSpryker\Shared\Sitemap\Dependency\Plugin\SitemapResolverPluginInterface
     */
    private function mockSitemapDatabaseResolver(): SitemapResolverPluginInterface
    {
        $sitemapDatabaseResolverMock = $this->createMock(SitemapResolverPluginInterface::class);
        $factory = $this->tester->mockFactoryMethod(self::METHOD_GET_SITEMAP_RESOLVER_PLUGIN, $sitemapDatabaseResolverMock);

        $this->sut->expects($this->once())
            ->method(self::METHOD_GET_FACTORY)
            ->willReturn($factory);

        return $sitemapDatabaseResolverMock;
    }
}
