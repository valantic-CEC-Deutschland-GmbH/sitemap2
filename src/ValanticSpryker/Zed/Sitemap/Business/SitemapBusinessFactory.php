<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use ValanticSpryker\Zed\Sitemap\Business\Model\Reader\SitemapReader;
use ValanticSpryker\Zed\Sitemap\Business\Model\Reader\SitemapReaderInterface;
use ValanticSpryker\Zed\Sitemap\Business\Supervisor\SitemapCreateSupervisor;
use ValanticSpryker\Zed\Sitemap\Business\Supervisor\SitemapCreateSupervisorInterface;
use ValanticSpryker\Zed\Sitemap\SitemapDependencyProvider;

/**
 * @method \ValanticSpryker\Zed\Sitemap\Persistence\SitemapEntityManagerInterface getEntityManager()
 * @method \ValanticSpryker\Zed\Sitemap\Persistence\SitemapRepositoryInterface getRepository()
 * @method \ValanticSpryker\Zed\Sitemap\SitemapConfig getConfig()
 */
class SitemapBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \ValanticSpryker\Zed\Sitemap\Business\Supervisor\SitemapCreateSupervisorInterface
     */
    public function createSitemapCreatorSupervisor(): SitemapCreateSupervisorInterface
    {
        return new SitemapCreateSupervisor(
            $this->getStoreFacade(),
            $this->getSitemapCreators(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig(),
        );
    }

    /**
     * @return \ValanticSpryker\Zed\Sitemap\Business\Model\Reader\SitemapReaderInterface
     */
    public function createSitemapReader(): SitemapReaderInterface
    {
        return new SitemapReader(
            $this->getRepository(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return array<\ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface>
     */
    protected function getSitemapCreators(): array
    {
        return $this->getProvidedDependency(SitemapDependencyProvider::PLUGIN_STACK_SITEMAP_CREATORS);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(SitemapDependencyProvider::FACADE_STORE);
    }
}
