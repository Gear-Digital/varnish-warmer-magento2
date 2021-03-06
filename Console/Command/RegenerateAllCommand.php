<?php
namespace LizardMedia\VarnishWarmer\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RegenerateAllCommand extends AbstractPurgeCommand
{
    /**
     * @var string
     */
    const CLI_COMMAND = 'lm-varnish:cache-all';

    /** @var string  */
    const BASE_URL = 'base-url';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::CLI_COMMAND)
            ->setDescription('Regenerate: homepage, categories, products')
            ->addOption(
                self::STORE_VIEW_ID,
                null,
                InputOption::VALUE_OPTIONAL
            )
            ->addOption(
                self::BASE_URL,
                null,
                InputOption::VALUE_OPTIONAL
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->varnishPurger->setStoreViewId((int) $input->getOption(self::STORE_VIEW_ID));
        $this->varnishPurger->setOverrideRegenBaseUrl($input->getOption(self::BASE_URL));
        $this->varnishPurger->regenerateAll();
    }
}
