<?php

declare(strict_types = 1);

namespace ValanticSpryker\Shared\Sitemap;

interface SitemapConstants
{
    /**
     * Specification:
     * - Use this constant to configure the amount of URLs per one XML file.
     *
     * @api
     *
     * @var string
     */
    public const SITEMAP_URL_LIMIT = 'SITEMAP:SITEMAP_URL_LIMIT';

    /**
     * Specification:
     * - Use this constant as XML file extension.
     *
     * @api
     *
     * @var string
     */
    public const DOT_XML_EXTENSION = '.xml';

    /**
     * Specification:
     * - Use this as naming as sitemap file.
     *
     * @api
     *
     * @var string
     */
    public const SITEMAP_NAME = 'sitemap';

    /**
     * Specification:
     * - sitemap index xmlns url example <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>.
     *
     * @api
     *
     * @var string
     */
    public const SITEMAP_NAMESPACE = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    /**
     * Specification:
     * - Flag whether to filter sitemap resources based on Spryker blacklist feature
     *
     * @var string
     */
    public const SITEMAP_USE_BLACKLISTS = 'SITEMAP:SITEMAP_USE_BLACKLISTS';
}
