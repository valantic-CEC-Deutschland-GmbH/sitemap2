<?php

declare(strict_types = 1);

namespace ValanticSpryker\Service\Sitemap;

use Generated\Shared\Transfer\SitemapFileTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \ValanticSpryker\Service\Sitemap\SitemapServiceFactory getFactory()
 */
class SitemapService extends AbstractService implements SitemapServiceInterface
{
 /**
  * @param array<\Generated\Shared\Transfer\SitemapUrlNodeTransfer> $urlList
  * @param int $page
  * @param string $storeName
  * @param string $fileType
  *
  * @return \Generated\Shared\Transfer\SitemapFileTransfer|null
  */
    public function createSitemapXmlFileTransfer(array $urlList, int $page, string $storeName, string $fileType): ?SitemapFileTransfer
    {
         return $this->getFactory()
                ->createSitemapXmlFileTransferCreator()
                ->createSitemapXmlFileTransfer($urlList, $page, $storeName, $fileType);
    }
}
