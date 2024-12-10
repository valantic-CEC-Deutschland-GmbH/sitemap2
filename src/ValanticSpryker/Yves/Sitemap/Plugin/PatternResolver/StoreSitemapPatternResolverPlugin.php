<?php

declare(strict_types = 1);

namespace ValanticSpryker\Yves\Sitemap\Plugin\PatternResolver;

use Spryker\Yves\Kernel\AbstractPlugin;
use ValanticSpryker\Yves\Sitemap\Dependency\Plugin\SitemapPatternResolverPluginInterface;

/**
 * @method \ValanticSpryker\Yves\Sitemap\SitemapFactory getFactory()
 */
class StoreSitemapPatternResolverPlugin extends AbstractPlugin implements SitemapPatternResolverPluginInterface
{
    /**
     * Takes into consideration the following paths:
     * - sitemap_{resourcePattern}_{storeName}_{number}.xml
     *
     * @param string $pattern
     * @param string $availableResourcesPattern
     *
     * @return string
     */
    public function resolvePattern(string $pattern, string $availableResourcesPattern): string
    {
        $storeName = $this->getStoreName();

        if ($storeName) {
            $storeNamePattern = '(\_(' . $storeName . '))?';
            $pattern = '(sitemap)' . $availableResourcesPattern . $storeNamePattern . '(\_[0-9]+)?\.xml';
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
}
