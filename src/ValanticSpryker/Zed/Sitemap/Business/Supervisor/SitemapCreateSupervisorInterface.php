<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Business\Supervisor;

interface SitemapCreateSupervisorInterface
{
    /**
     * @param string $storeName
     *
     * @return void
     */
    public function create(string $storeName): void;
}
