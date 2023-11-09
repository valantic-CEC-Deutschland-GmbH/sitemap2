<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sitemap\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \ValanticSpryker\Zed\Sitemap\Business\SitemapFacadeInterface getFacade()
 */
class SitemapGenerateConsole extends Console
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'sitemap:generate';

    /**
     * @var string
     */
    protected const DESCRIPTION = 'Trigger sitemap generation.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription(self::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $messenger = $this->getMessenger();

        $messenger->info(sprintf(
            'Started %s!',
            self::COMMAND_NAME,
        ));

        $this->getFacade()->createSitemapXml();

        return static::CODE_SUCCESS;
    }
}
