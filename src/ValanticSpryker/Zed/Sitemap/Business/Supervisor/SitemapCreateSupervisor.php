<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Business\Supervisor;

use DOMDocument;
use Generated\Shared\Transfer\SitemapFileTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use ValanticSpryker\Shared\Sitemap\SitemapConstants;
use ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface;
use ValanticSpryker\Zed\Sitemap\Persistence\SitemapEntityManagerInterface;
use ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface;
use ValanticSpryker\Zed\Sitemap\SitemapConfig;

class SitemapCreateSupervisor implements SitemapCreateSupervisorInterface
{
    protected const EXCLUDED_SITEMAP_FILE_NAMES = [
        'sitemap.xml',
    ];

    protected StoreFacadeInterface $storeFacade;

    protected array $sitemapCreatorPlugins;

    protected SitemapEntityManagerInterface $entityManager;

    protected SitemapRepositoryInterface $repository;

    protected SitemapConfig $config;

    /**
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     * @param array<\ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface> $sitemapCreatorPlugins
     * @param \ValanticSpryker\Zed\Sitemap\Persistence\SitemapEntityManagerInterface $entityManager
     * @param \ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface $repository
     * @param \ValanticSpryker\Zed\Sitemap\SitemapConfig $config
     */
    public function __construct(
        StoreFacadeInterface $storeFacade,
        array $sitemapCreatorPlugins,
        SitemapEntityManagerInterface $entityManager,
        SitemapRepositoryInterface $repository,
        SitemapConfig $config
    ) {
        $this->storeFacade = $storeFacade;
        $this->sitemapCreatorPlugins = $sitemapCreatorPlugins;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function create(): void
    {
        $sitemapList = [];

        foreach ($this->sitemapCreatorPlugins as $sitemapCreator) {
            if (!$sitemapCreator instanceof SitemapCreatorPluginInterface) {
                continue;
            }

            $sitemapList = array_merge(
                $sitemapList,
                $sitemapCreator->createSitemapXml(),
            );
        }

        $this->removeUnnecessarySitemapFiles($sitemapList);
        $this->storeSitemapFiles($sitemapList);
        $this->createSitemapIndex();
    }

    /**
     * @param array $sitemapList
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
     *
     * @return void
     */
    protected function removeUnnecessarySitemapFiles(array $sitemapList): void
    {
        $savedSitemapNames = [];

        foreach ($sitemapList as $sitemapFileTransfer) {
            $savedSitemapNames[] = $sitemapFileTransfer->getName();
        }

        $storeName = $this->storeFacade->getCurrentStore()->getName();
        $sitemapsToRemove = $this->repository->findAllSitemapsByStoreNameExceptWithGivenNames($storeName, $savedSitemapNames);

        foreach ($sitemapsToRemove as $sitemapToRemove) {
            $this->entityManager->removeSitemap($sitemapToRemove->getIdSitemap());
        }
    }

    /**
     * @return void
     */
    protected function createSitemapIndex(): void
    {
        $sitemapList = $this->repository->findAllSitemapsExceptWithGivenNames(static::EXCLUDED_SITEMAP_FILE_NAMES);

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
            ->setContent($domTree->saveXML());

        return $sitemapFileTransfer;
    }
}
