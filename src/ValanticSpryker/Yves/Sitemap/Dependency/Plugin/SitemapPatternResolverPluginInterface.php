<?php

declare(strict_types = 1);

namespace ValanticSpryker\Yves\Sitemap\Dependency\Plugin;

interface SitemapPatternResolverPluginInterface
{
    /**
     * @param string $pattern
     * @param string $availableResourcesPattern
     *
     * @return string
     */
    public function resolvePattern(string $pattern, string $availableResourcesPattern): string;
}
