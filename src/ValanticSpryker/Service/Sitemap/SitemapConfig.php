<?php

declare(strict_types = 1);

namespace ValanticSpryker\Service\Sitemap;

use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Shared\Application\ApplicationConstants;

class SitemapConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getYvesBaseUrl(): string
    {
        return $this->get(ApplicationConstants::BASE_URL_YVES);
    }
}
