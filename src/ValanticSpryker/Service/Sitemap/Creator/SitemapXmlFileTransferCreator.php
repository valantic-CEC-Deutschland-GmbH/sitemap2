<?php

declare(strict_types = 1);

namespace ValanticSpryker\Service\Sitemap\Creator;

use DOMDocument;
use Generated\Shared\Transfer\SitemapFileTransfer;
use ValanticSpryker\Shared\Sitemap\SitemapConstants;

class SitemapXmlFileTransferCreator
{
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
            $urlNode = $domtree->createElement('url');
            $urlNode = $urlSet->appendChild($urlNode);
            $urlNode->appendChild($domtree->createElement('loc', htmlspecialchars($url->getUrl())));
            $urlNode->appendChild($domtree->createElement('lastmod', $url->getUpdatedAt()));
        }

        return $this->createSitemapFileTransfer($filename, $domtree->saveXML());
    }

    /**
     * @param string $filename
     * @param string $sitemapContent
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    protected function createSitemapFileTransfer(string $filename, string $sitemapContent): SitemapFileTransfer
    {
        $sitemapFileTransfer = (new SitemapFileTransfer())
            ->setName($filename)
            ->setContent($sitemapContent);

        return $sitemapFileTransfer;
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
}
