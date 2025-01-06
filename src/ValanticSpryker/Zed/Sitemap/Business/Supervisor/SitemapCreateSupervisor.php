<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Business\Supervisor;

use DOMDocument;
use Generated\Shared\Transfer\SitemapFileTransfer;
use ValanticSpryker\Shared\Sitemap\SitemapConstants;
use ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface;
use ValanticSpryker\Zed\Sitemap\Persistence\SitemapEntityManagerInterface;
use ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface;

class SitemapCreateSupervisor implements SitemapCreateSupervisorInterface
{
    protected const EXCLUDED_SITEMAP_FILE_NAMES = [
        SitemapConstants::SITEMAP_NAME . SitemapConstants::DOT_XML_EXTENSION,
    ];

    /**
     * @var array<\ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface>
     */
    protected array $sitemapCreatorPlugins;

    protected SitemapEntityManagerInterface $entityManager;

    protected SitemapRepositoryInterface $repository;

    /**
     * @param array<\ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface> $sitemapCreatorPlugins
     * @param \ValanticSpryker\Zed\Sitemap\Persistence\SitemapEntityManagerInterface $entityManager
     * @param \ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface $repository
     */
    public function __construct(
        array $sitemapCreatorPlugins,
        SitemapEntityManagerInterface $entityManager,
        SitemapRepositoryInterface $repository
    ) {
        $this->sitemapCreatorPlugins = $sitemapCreatorPlugins;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @param string $storeName
     *
     * @return void
     */
    public function create(string $storeName): void
    {
        $sitemapList = [];

        foreach ($this->sitemapCreatorPlugins as $sitemapCreator) {
            if (!$sitemapCreator instanceof SitemapCreatorPluginInterface) {
                continue;
            }

            $sitemapList = array_merge(
                $sitemapList,
                $sitemapCreator->createSitemapXml($storeName),
            );
        }

        $this->removeUnnecessarySitemapFiles($sitemapList, $storeName);
        $this->storeSitemapFiles($sitemapList);
        $this->createSitemapIndex();
    }

    /**
     * @param array<\Generated\Shared\Transfer\SitemapFileTransfer> $sitemapList
     *
     * @return void
     */
    protected function storeSitemapFiles(array $sitemapList): void
    {
        foreach ($sitemapList as $sitemapFileTransfer) {
            $this->entityManager->saveSitemapFile($sitemapFileTransfer);
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\SitemapFileTransfer> $sitemapList
     * @param string $storeName
     *
     * @return void
     */
    protected function removeUnnecessarySitemapFiles(array $sitemapList, string $storeName): void
    {
        $savedSitemapNames = [];

        foreach ($sitemapList as $sitemapFileTransfer) {
            if (!$sitemapFileTransfer->getName()) {
                continue;
            }

            $savedSitemapNames[] = $sitemapFileTransfer->getName();
        }

        $sitemapsToRemove = $this->repository->findAllSitemapsByStoreNameExceptWithGivenNames($storeName, $savedSitemapNames);

        foreach ($sitemapsToRemove as $sitemapToRemove) {
            if (!$sitemapToRemove->getIdSitemap()) {
                return;
            }

            $this->entityManager->removeSitemap($sitemapToRemove->getIdSitemap());
        }
    }

    /**
     * @return void
     */
    protected function createSitemapIndex(): void
    {
        $sitemapList = $this->repository->findAllSitemapsByStoreNameExceptWithGivenNames(
            null,
            self::EXCLUDED_SITEMAP_FILE_NAMES,
        );

        $domTree = new DOMDocument('1.0', 'UTF-8');

        $domTree->preserveWhiteSpace = false;
        $domTree->formatOutput = true;

        $sitemapIndex = $domTree->createElementNS(SitemapConstants::SITEMAP_NAMESPACE, 'sitemapindex');
        $sitemapIndex = $domTree->appendChild($sitemapIndex);

        foreach ($sitemapList as $sitemapItem) {
            $sitemapIndexNode = $domTree->createElement('sitemap');
            $sitemapIndexNode = $sitemapIndex->appendChild($sitemapIndexNode);

            $domElement = $domTree->createElement('loc', $sitemapItem->getYvesBaseUrl() . '/' . $sitemapItem->getName());

            $sitemapIndexNode->appendChild(
                $domElement,
            );
        }

        $sitemapFileTransfer = $this->createSitemapFileTransfer($domTree);
        $this->entityManager->saveSitemapFile($sitemapFileTransfer);
    }

    /**
     * @param \DOMDocument $domTree
     *
     * @return \Generated\Shared\Transfer\SitemapFileTransfer
     */
    protected function createSitemapFileTransfer(DOMDocument $domTree): SitemapFileTransfer
    {
        $sitemapFileTransfer = new SitemapFileTransfer();
        $sitemapFileTransfer
            ->setName(SitemapConstants::SITEMAP_NAME . SitemapConstants::DOT_XML_EXTENSION)
            ->setContent((string)$domTree->saveXML());

        return $sitemapFileTransfer;
    }
}
