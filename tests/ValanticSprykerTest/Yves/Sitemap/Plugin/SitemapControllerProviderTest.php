<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Yves\Sitemap\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreBuilder;
use Spryker\Client\Store\StoreClient;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Yves\Router\Route\RouteCollection;
use ValanticSpryker\Yves\Sitemap\Plugin\PatternResolver\StoreSitemapPatternResolverPlugin;
use ValanticSpryker\Yves\Sitemap\Plugin\Provider\SitemapControllerProvider;
use ValanticSpryker\Yves\Sitemap\SitemapDependencyProvider;
use ValanticSprykerTest\Yves\Sitemap\SitemapYvesTester;

/**
 * @group Sitemap
 */
class SitemapControllerProviderTest extends Unit
{
    private const METHOD_GET_STORE_CLIENT = 'getStoreClient';
    private const METHOD_GET_CURRENT_STORE = 'getCurrentStore';
    private const METHOD_GET_AVAILABLE_RESOURCE_TYPES = 'getAvailableResourceTypes';
    private const METHOD_GET_SITEMAP_PATTERN_RESOLVER_PLUGINS = 'getSitemapPatternResolverPlugins';

    protected SitemapYvesTester $tester;

    private SitemapControllerProvider $sut;

    /**
     * @var \Spryker\Client\Store\StoreClientInterface|\PHPUnit\Framework\MockObject\MockObject
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
        $this->tester->mockFactoryMethod(
            self::METHOD_GET_AVAILABLE_RESOURCE_TYPES,
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
        $storeTransfer = (new StoreBuilder(['name' => 'de']))->build();
        $this->storeClientMock->expects($this->once())
            ->method(self::METHOD_GET_CURRENT_STORE)
            ->willReturn($storeTransfer);

        $this->tester->mockFactoryMethod(self::METHOD_GET_SITEMAP_PATTERN_RESOLVER_PLUGINS, [
            new StoreSitemapPatternResolverPlugin(),
        ]);
        $factory = $this->tester->mockFactoryMethod(self::METHOD_GET_STORE_CLIENT, $this->storeClientMock);
        $factory->setContainer($this->tester->getModuleContainer());
        $this->sut->setFactory($factory);

        $routeCollection = new RouteCollection();
        $routeCollection = $this->sut->addRoutes($routeCollection);
        $route = $routeCollection->getIterator()->current();

        $this->assertEquals(1, $routeCollection->count());
        $this->assertEquals('(sitemap)(\_abstract_product|\_categories|\_cms)?(\_(de))?(\_[0-9]+)?\.xml', $route->getRequirements()['name']);
    }

    /**
     * @return void
     */
    public function testProviderAddsCorrectRouteWithoutPlugins(): void
    {
        $factory = $this->tester->mockFactoryMethod(self::METHOD_GET_SITEMAP_PATTERN_RESOLVER_PLUGINS, []);
        $factory->setContainer($this->tester->getModuleContainer());
        $this->sut->setFactory($factory);

        $routeCollection = new RouteCollection();
        $routeCollection = $this->sut->addRoutes($routeCollection);
        $route = $routeCollection->getIterator()->current();

        $this->assertEquals(1, $routeCollection->count());
        $this->assertEquals('(sitemap)(\_abstract_product|\_categories|\_cms)?(\_[0-9]+)?\.xml', $route->getRequirements()['name']);
    }
}
