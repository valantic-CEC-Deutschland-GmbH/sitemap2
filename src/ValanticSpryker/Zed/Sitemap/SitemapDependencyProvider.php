<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class SitemapDependencyProvider extends AbstractBundleDependencyProvider
{
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

        return $container;
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
