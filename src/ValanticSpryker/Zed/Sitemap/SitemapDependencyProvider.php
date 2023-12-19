<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class SitemapDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const PLUGIN_STACK_SITEMAP_CREATORS = 'PLUGIN_STACK_SITEMAP_CREATORS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $this->addSitemapCreatorPluginStack($container);
        $this->addFacadeStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $this->addFacadeLocale($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addFacadeLocale(Container $container): void
    {
        $container->set(self::FACADE_LOCALE, static function (Container $container): LocaleFacadeInterface {
            return $container->getLocator()->locale()->facade();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addFacadeStore(Container $container): void
    {
        $container->set(self::FACADE_STORE, static function (Container $container): StoreFacadeInterface {
            return $container->getLocator()->store()->facade();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSitemapCreatorPluginStack(Container $container): Container
    {
        $container->set(self::PLUGIN_STACK_SITEMAP_CREATORS, function () {
            return $this->getSitemapCreatorPluginStack();
        });

        return $container;
    }

    /**
     * @return array<\ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface>
     */
    protected function getSitemapCreatorPluginStack(): array
    {
        return [

        ];
    }
}
