<?php

declare(strict_types = 1);

namespace ValanticSpryker\Service\Sitemap\Creator;

use DateTime;
use DOMDocument;
use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\SitemapUrlTransfer;
use League\Uri\Uri;
use ValanticSpryker\Service\Sitemap\SitemapConfig;
use ValanticSpryker\Shared\Sitemap\SitemapConstants;

class SitemapXmlFileTransferCreator
{
    protected const TAG_URL = 'url';
    protected const TAG_LOC = 'loc';
    protected const TAG_LAST_MOD = 'lastmod';
    protected const TAG_PRIORITY = 'priority';

    protected SitemapConfig $config;

    /**
     * @param \ValanticSpryker\Service\Sitemap\SitemapConfig $config
     */
    public function __construct(SitemapConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SitemapUrlTransfer> $urlList
     * @param int $page
     * @param string $storeName
     * @param string $fileType
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer|null
     */
    public function createSitemapXmlFileTransfer(
        array $urlList,
        int $page,
        string $storeName,
        string $fileType
    ): ?SitemapFileTransfer {
        if (count($urlList) === 0) {
            return null;
        }

        $filename = $this->createSitemapPaginationFileName($storeName, $fileType, $page);
        $domtree = new DOMDocument('1.0', 'UTF-8');

        $domtree->preserveWhiteSpace = false;
        $domtree->formatOutput = true;

        $urlSet = $domtree->createElementNS(SitemapConstants::SITEMAP_NAMESPACE, 'urlset');
        $urlSet = $domtree->appendChild($urlSet);

        /** @var \Generated\Shared\Transfer\SitemapUrlTransfer $url */
        foreach ($urlList as $url) {
            $urlNode = $domtree->createElement(self::TAG_URL);
            $urlNode = $urlSet->appendChild($urlNode);
            $urlNode->appendChild($domtree->createElement(self::TAG_LOC, $this->prepareUrl($url)));
            $urlNode->appendChild($domtree->createElement(self::TAG_LAST_MOD, $this->updateToCorrectDateFormat($url->getUpdatedAt())));
            $urlNode->appendChild($domtree->createElement(self::TAG_PRIORITY, '1.0'));
        }

        return $this->createSitemapFileTransfer($filename, $domtree->saveXML(), $storeName);
    }

    /**
     * @param string $filename
     * @param string $sitemapContent
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    protected function createSitemapFileTransfer(string $filename, string $sitemapContent, string $storeName): SitemapFileTransfer
    {
        return (new SitemapFileTransfer())
            ->setStoreName($storeName)
            ->setYvesBaseUrl($this->config->getYvesBaseUrl())
            ->setName($filename)
            ->setContent($sitemapContent);
    }

    /**
     * @param string $storeName
     * @param string $fileType
     * @param int $pageNumber
     *
     * @return string
     */
    protected function createSitemapPaginationFileName(
        string $storeName,
        string $fileType,
        int $pageNumber
    ): string {
        return sprintf(
            '%s_%s_%s_%s%s',
            SitemapConstants::SITEMAP_NAME,
            $fileType,
            strtolower($storeName),
            $pageNumber,
            SitemapConstants::DOT_XML_EXTENSION,
        );
    }

    /**
     * @param string $dateTime
     *
     * @return string
     */
    protected function updateToCorrectDateFormat(string $dateTime): string
    {
        return (new DateTime($dateTime))->format(SitemapConfig::LAST_MOD_FORMAT);
    }

    /**
     * @param \Generated\Shared\Transfer\SitemapUrlTransfer $url
     *
     * @return string
     */
    protected function prepareUrl(SitemapUrlTransfer $url): string
    {
        $trimmed = rtrim($url->getUrl(), '/');
        $encodedUrl = Uri::createFromString($trimmed)->toString();
        return htmlspecialchars($encodedUrl);
    }
}
