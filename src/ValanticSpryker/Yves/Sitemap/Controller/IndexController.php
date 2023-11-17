<?php

declare(strict_types = 1);

namespace ValanticSpryker\Yves\Sitemap\Controller;

use Generated\Shared\Transfer\SitemapRequestTransfer;
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

        $sitemapRequestTransfer = $this->createSitemapRequestTransfer()
            ->setFilename($filename);

        $sitemapResponseTransfer = $this->getFactory()
            ->getSitemapClient()
            ->getSitemap($sitemapRequestTransfer);

        if (!$sitemapResponseTransfer->getIsSuccessful()) {
            throw new NotFoundHttpException();
        }

        $response = new Response($sitemapResponseTransfer->getSitemapFile()->getContent());
        $response->headers->set('Content-Type', 'application/xml');

        return $response;
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
