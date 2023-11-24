<?php

declare(strict_types = 1);

namespace ValanticSpryker\Shared\Sitemap\Dependency\Plugin;

use Generated\Shared\Transfer\SitemapRequestTransfer;
use Generated\Shared\Transfer\SitemapResponseTransfer;

interface SitemapResolverPluginInterface
{
    /**
     * Specification:
     * - Resolves Sitemap from specific location (Redis, Database, ... )
     *
     *
     * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapTransfer
     *
     * @return \Generated\Shared\Transfer\SitemapResponseTransfer
     */
    public function getSitemap(SitemapRequestTransfer $sitemapTransfer): SitemapResponseTransfer;
}
