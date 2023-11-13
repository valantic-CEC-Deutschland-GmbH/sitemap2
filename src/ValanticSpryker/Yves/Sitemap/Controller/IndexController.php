<?php

namespace ValanticSpryker\Yves\Sitemap\Controller;

use Generated\Shared\Transfer\SitemapRequestTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \ValanticSpryker\Yves\Sitemap\SitemapFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $filename = $this->formatFilename($request->getPathInfo());

        $sitemapRequestTransfer = $this->createSitemapRequestTransfer()
            ->setFilename($filename);

        $sitemapContent = $this->getFactory()
            ->getSitemapClient()
            ->getSitemap($sitemapRequestTransfer);

        //@todo add isSuccess to sitemap request transfer
//        if ($sitemapContent->getIsSuccess() === false) {
//            throw new NotFoundHttpException();
//        }

        $response = new Response($sitemapContent->getContent());
        $response->headers->set('Content-Type', 'application/xml');

        return $response;
    }

    /**
     * @return string
     */
    protected function getLocale(): string
    {
        return explode('_', (Store::getInstance())->getCurrentLocale())[0];
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    protected function formatFilename(string $filename): string
    {
        $filename = array_reverse(explode('/', $filename));

        return $filename[0];
    }

    /**
     * @return \Generated\Shared\Transfer\SitemapRequestTransfer
     */
    protected function createSitemapRequestTransfer(): SitemapRequestTransfer
    {
        return new SitemapRequestTransfer();
    }
}
