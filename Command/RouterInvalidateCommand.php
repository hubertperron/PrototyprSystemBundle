<?php

namespace Prototypr\SystemBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * A console command for invalidating (clearing) the router cache
 */
class RouterInvalidateCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('prototypr:router:invalidate')
            ->setDescription('Invalidate the router cache')
            ->setHelp(<<<EOF
The <info>prototypr:router:invalidate</info> invalidate (clear) the router cache

<info>php app/console prototypr:router:invalidate --env=dev</info>
EOF
        )
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel = $this->getContainer()->get('kernel');
        $output->writeln(sprintf('Invalidating routes for the <info>%s</info> environment', $kernel->getEnvironment()));

        $this->getContainer()->get('prototypr_system.router_cache')->invalidate();
    }

}
