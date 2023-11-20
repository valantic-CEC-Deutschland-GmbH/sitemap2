<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Yves\Sitemap\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreBuilder;
use Spryker\Client\Store\StoreClient;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Yves\Router\Route\RouteCollection;
use ValanticSpryker\Yves\Sitemap\Plugin\Provider\SitemapControllerProvider;
use ValanticSpryker\Yves\Sitemap\SitemapDependencyProvider;
use ValanticSprykerTest\Yves\Sitemap\SitemapYvesTester;

/**
 * @group Sitemap
 */
class SitemapControllerProviderTest extends Unit
{
    private const METHOD_GET_CURRENT_STORE = 'getCurrentStore';

    protected SitemapYvesTester $tester;

    private SitemapControllerProvider $sut;

    /**
     * @var \Spryker\Client\Store\StoreClientInterface|\ValanticSprykerTest\Yves\Sitemap\Plugin\MockObject
     */
    private StoreClientInterface $storeClientMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->storeClientMock = $this->createMock(StoreClient::class);
        $this->tester->setDependency(SitemapDependencyProvider::CLIENT_STORE, $this->storeClientMock);
        $this->tester->setDependency(
            SitemapDependencyProvider::RESOURCES_SITEMAP,
            [
                'abstract_product',
                'categories',
                'cms',
            ],
        );
        $this->sut = new SitemapControllerProvider();
    }

    /**
     * @return void
     */
    public function testProviderAddsCorrectRouteWhenStoreIsSet(): void
    {
        $storeTransfer = (new StoreBuilder(['name' => getenv('APPLICATION_STORE')]))->build();
        $this->storeClientMock->method(self::METHOD_GET_CURRENT_STORE)
            ->willReturn($storeTransfer);
        $this->tester->setDependency(SitemapDependencyProvider::CLIENT_STORE, $this->storeClientMock);

        $routeCollection = new RouteCollection();
        $routeCollection = $this->sut->addRoutes($routeCollection);
        $route = $routeCollection->getIterator()->current();

        $this->assertEquals(1, $routeCollection->count());
        $this->assertEquals('(sitemap)(\_abstract_product|\_categories|\_cms)?(\_(de))?(\_[0-9]+)?\.xml', $route->getRequirements()['name']);
    }

    /**
     * @return void
     */
    public function testProviderAddsCorrectRouteWhenStoreNotSet(): void
    {
        $this->storeClientMock = $this->createMock(StoreClient::class);
        $this->tester->setDependency(SitemapDependencyProvider::CLIENT_STORE, $this->storeClientMock);
        $this->tester->mockFactoryMethod('getStoreClient', $this->storeClientMock);

        $storeTransfer = (new StoreBuilder(['name' => null]))->build();
        $this->storeClientMock->method(self::METHOD_GET_CURRENT_STORE)
            ->willReturn($storeTransfer);

        $routeCollection = new RouteCollection();
        $routeCollection = $this->sut->addRoutes($routeCollection);
        $route = $routeCollection->getIterator()->current();

        $this->assertEquals(1, $routeCollection->count());
        $this->assertEquals('(sitemap)(\_abstract_product|\_categories|\_cms)?(\_[0-9]+)?\.xml', $route->getRequirements()['name']);
    }
}
