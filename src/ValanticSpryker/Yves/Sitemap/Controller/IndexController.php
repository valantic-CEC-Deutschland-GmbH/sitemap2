<?php

namespace ValanticSpryker\Yves\Sitemap\Controller;

use Generated\Shared\Transfer\SitemapTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \ValanticSpryker\Yves\Sitemap\SitemapFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $filename = $this->formatFilename($request->getPathInfo());

        $sitemapTransfer = $this->createSitemapTransfer()
            ->setFile($filename)
            ->setLocale($this->getLocale());

        $sitemapContent = $this->getFactory()
            ->getSitemapClient()
            ->getSitemap($sitemapTransfer);

        if ($sitemapContent->getIsSuccess() === false) {
            throw new NotFoundHttpException();
        }

        $response = new Response($sitemapContent->getUrls());
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
     * @return \Generated\Shared\Transfer\SitemapTransfer
     */
    protected function createSitemapTransfer(): SitemapTransfer
    {
        return new SitemapTransfer();
    }
}
