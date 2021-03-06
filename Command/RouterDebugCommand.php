<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Prototypr\SystemBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

use Symfony\Bundle\FrameworkBundle\Command\RouterDebugCommand as BaseRouterDebugCommand;

/**
 * A console command for retrieving information about routes
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class RouterDebugCommand extends BaseRouterDebugCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('prototypr:router:debug')
            ->setDefinition(array(
                new InputArgument('name', InputArgument::OPTIONAL, 'A route name'),
            ))
            ->setDescription('Displays current routes for an application')
            ->setHelp(<<<EOF
The <info>%command.name%</info> displays the configured routes:

  <info>php %command.full_name%</info>
EOF
            )
        ;
    }

    protected function outputRoutes(OutputInterface $output, $routes = null)
    {
        if (null === $routes) {
            $routes = array();
            foreach ($this->getContainer()->get('router')->getRouteCollection()->all() as $name => $route) {
                $routes[$name] = $route->compile();
            }
        }

        $output->writeln($this->getHelper('formatter')->formatSection('router', 'Current routes'));

        $maxName = 4;
        $maxMethod = 6;
        foreach ($routes as $name => $route) {
            $requirements = $route->getRequirements();
            $method = isset($requirements['_method']) ? strtoupper(is_array($requirements['_method']) ? implode(', ', $requirements['_method']) : $requirements['_method']) : 'ANY';

            if (strlen($name) > $maxName) {
                $maxName = strlen($name);
            }

            if (strlen($method) > $maxMethod) {
                $maxMethod = strlen($method);
            }
        }
        $format  = '%-'.$maxName.'s %-'.$maxMethod.'s %s';

        // displays the generated routes
        $format1  = '%-'.($maxName + 19).'s %-'.($maxMethod + 19).'s %s';
        $output->writeln(sprintf($format1, '<comment>Name</comment>', '<comment>Method</comment>', '<comment>Pattern</comment>'));
        foreach ($routes as $name => $route) {
            $requirements = $route->getRequirements();
            $method = isset($requirements['_method']) ? strtoupper(is_array($requirements['_method']) ? implode(', ', $requirements['_method']) : $requirements['_method']) : 'ANY';
            $pattern = $route->getPattern();
            if ($pageSlug = $route->getRoute()->getDefault('_prototypr_page_slug')) {
                $pattern = preg_replace('/\/(' . preg_quote($pageSlug, '/') . ')/', '/<fg=green>$1</>', $pattern);
            }
            if ($parentSlugs = $route->getRoute()->getDefault('_prototypr_page_parent_slugs_imploded')) {
                $pattern = preg_replace('/(' . preg_quote($parentSlugs, '/'). ')/', '<fg=blue>$1</>', $pattern);
            }
            if ($application = $route->getRoute()->getDefault('_prototypr_application_route_prefix')) {
                $pattern = preg_replace('/(' . preg_quote($application, '/'). ')/', '<fg=red>$1</>', $pattern);
            }
            $output->writeln(sprintf($format, $name, $method, $pattern));
        }
    }

}
