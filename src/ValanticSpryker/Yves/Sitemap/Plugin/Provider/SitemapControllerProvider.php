<?php

declare(strict_types = 1);

namespace ValanticSpryker\Yves\Sitemap\Plugin\Provider;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

/**
 * @method \ValanticSpryker\Yves\Sitemap\SitemapFactory getFactory()
 */
class SitemapControllerProvider extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const SITEMAP_INDEX = 'sitemap-index';

    /**
     * @todo look for a way to refactor according to available connectors
     *
     * @var array
     */
    public const AVAILABLE_RESOURCES = [
        'products',
        'categories',
        'cms',
    ];

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addSitemapRoutes($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSitemapRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildGetRoute('/{name}', 'Sitemap', 'Index', 'indexAction');
        $route->setRequirement('name', $this->getSitemapPattern());
        $routeCollection->add(static::SITEMAP_INDEX, $route);

        return $routeCollection;
    }

    /**
     * Takes into consideration the following paths:
     * - sitemap_products_{storeName}_{number}.xml
     * - sitemap_categories_{storeName}_{number}.xml
     * - sitemap_cms_{storeName}_{number}.xml
     * - sitemap_{number}.xml
     * - sitemap.xml
     *
     * @return string
     */
    protected function getSitemapPattern(): string
    {
        $storeNames = $this->getStoreNames();
        $availableResourcesPattern = $this->getAvailableResourcesPattern();

        if ($storeNames) {
            $storeNamePattern = '(\_(' . implode('|', $storeNames) . '))?';
            $pattern = '(sitemap)' . $availableResourcesPattern . $storeNamePattern . '(\_[0-9]+)?\.xml';
        } else {
            $pattern = '(sitemap)' . $availableResourcesPattern . '(\_[0-9]+)?\.xml';
        }

        return $pattern;
    }

    /**
     * @return array
     */
    protected function getStoreNames(): array
    {
        //@todo remove usage of getStoresWithSharedPersistence (deprecated)
        //@todo refactor console command to use correct store for urls
        $currentStore = $this->getFactory()
            ->getStoreClient()
            ->getCurrentStore();

        $storeName = $currentStore->getName();
        $otherStores = array_values($currentStore->getStoresWithSharedPersistence());

        $storeNames = array_merge($otherStores, [$storeName]);

        return array_map('strtolower', $storeNames);
    }

    /**
     * @return string
     */
    protected function getAvailableResourcesPattern(): string
    {
        if (!static::AVAILABLE_RESOURCES) {
            return '';
        }

        return '(\_' . implode('|\_', array_values(static::AVAILABLE_RESOURCES)) . ')?';
    }
}
