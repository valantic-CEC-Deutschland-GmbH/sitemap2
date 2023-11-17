<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Yves\Sitemap\Controller;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\SitemapFileBuilder;
use Generated\Shared\Transfer\SitemapResponseTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ValanticSpryker\Client\Sitemap\SitemapClient;
use ValanticSpryker\Client\Sitemap\SitemapClientInterface;
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
    private const XML_CONTENT_TYPE = 'application/xml';

    protected SitemapYvesTester $tester;

    private IndexController $sut;

    /**
     * @var \ValanticSpryker\Client\Sitemap\SitemapClientInterface|\ValanticSprykerTest\Yves\Sitemap\Controller\MockObject
     */
    private SitemapClientInterface $sitemapClientMock;

    /**
     * @return void
     */
    protected function _setup(): void
    {
        parent::_setup();

        $this->sitemapClientMock = $this->createMock(SitemapClient::class);
        $this->tester->setDependency(SitemapDependencyProvider::CLIENT_SITEMAP, $this->sitemapClientMock);

        $this->sut = new IndexController();
    }

    /**
     * @return void
     */
    public function testIndexActionThrowsExceptionIfFileNotFound(): void
    {
        $request = Request::create(self::FILENAME_SITEMAP_XML);
        $responseTransfer = (new SitemapResponseTransfer())
            ->setIsSuccessful(false)
            ->setSitemapFile(null);

        $this->sitemapClientMock->expects($this->any())
            ->method(self::METHOD_GET_SITEMAP)
            ->willReturn($responseTransfer);

        $this->expectException(NotFoundHttpException::class);

        $response = $this->sut->indexAction($request);
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

        $this->sitemapClientMock->expects($this->any())
            ->method(self::METHOD_GET_SITEMAP)
            ->willReturn($responseTransfer);

        $response = $this->sut->indexAction($request);

        $this->assertEquals(self::XML_CONTENT_TYPE, $response->headers->get('Content-Type'));
        $this->assertEquals($sitemapFile->getContent(), $response->getContent());
    }
}
