<?php

declare(strict_types = 1);

namespace ValanticSpryker\Yves\Sitemap\Plugin\Resolver;

use Generated\Shared\Transfer\SitemapRequestTransfer;
use Generated\Shared\Transfer\SitemapResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use ValanticSpryker\Shared\Sitemap\Dependency\Plugin\SitemapResolverPluginInterface;

/**
 * @method \ValanticSpryker\Yves\Sitemap\SitemapFactory getFactory()
 */
class SitemapDatabaseResolverPlugin extends AbstractPlugin implements SitemapResolverPluginInterface
{
 /**
  * @param \Generated\Shared\Transfer\SitemapRequestTransfer $sitemapTransfer
  *
  * @return \Generated\Shared\Transfer\SitemapResponseTransfer
  */
    public function getSitemap(SitemapRequestTransfer $sitemapTransfer): SitemapResponseTransfer
    {
        return $this->getFactory()
            ->getSitemapClient()
            ->getSitemap($sitemapTransfer);
    }
}
