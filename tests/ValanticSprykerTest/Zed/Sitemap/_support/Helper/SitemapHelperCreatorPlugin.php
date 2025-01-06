<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Zed\Sitemap\Helper;

use Generated\Shared\DataBuilder\SitemapFileBuilder;
use ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface;

class SitemapHelperCreatorPlugin implements SitemapCreatorPluginInterface
{
    public const TEST_CONTENT = <<<EOD
    <?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
          <url>
            <loc>http://test/</loc>
            <lastmod>2023-11-13 07:30:55</lastmod>
          </url>
        </urlset>
    EOD;

    public const TEST_BASE_URL = 'test.com/test-loc';
    public const TEST_NAME = 'test-name';

    /**
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function createSitemapXml(string $storeName): array
    {
        $sitemapFileTransfer = (new SitemapFileBuilder([
            'name' => static::TEST_NAME,
            'content' => static::TEST_CONTENT,
            'yvesBaseUrl' => static::TEST_BASE_URL,
            'storeName' => $storeName,
        ]))->build();

        return [$sitemapFileTransfer];
    }
}
