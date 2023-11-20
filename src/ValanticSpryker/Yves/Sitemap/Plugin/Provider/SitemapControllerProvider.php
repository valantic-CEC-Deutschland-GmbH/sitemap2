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
        $storeName = $this->getStoreName();
        $availableResourcesPattern = $this->getAvailableResourcesPattern();

        if ($storeName) {
            $storeNamePattern = '(\_(' . $storeName . '))?';
            $pattern = '(sitemap)' . $availableResourcesPattern . $storeNamePattern . '(\_[0-9]+)?\.xml';
        } else {
            $pattern = '(sitemap)' . $availableResourcesPattern . '(\_[0-9]+)?\.xml';
        }

        return $pattern;
    }

    /**
     * @return string|null
     */
    protected function getStoreName(): ?string
    {
        $currentStore = $this->getFactory()
            ->getStoreClient()
            ->getCurrentStore();

        $storeName = $currentStore->getName();

        return $storeName ? strtolower($storeName) : null;
    }

    /**
     * @return string
     */
    protected function getAvailableResourcesPattern(): string
    {
        $availableResources = $this
            ->getFactory()
            ->getAvailableResourceTypes();

        if (!$availableResources) {
            return '';
        }

        $availableResources = $this->prepareResourceStringsForRegex($availableResources);

        return '(\_' . implode('|\_', $availableResources) . ')?';
    }

    /**
     * @param array<string> $availableResources
     *
     * @return array<string>
     */
    protected function prepareResourceStringsForRegex(array $availableResources): array
    {
        return array_map(static function (string $resource): string {
            return preg_quote($resource, '/');
        }, $availableResources);
    }
}
