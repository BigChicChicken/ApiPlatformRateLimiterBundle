<?php

/*
 * This file is part of the ApiPlatformRateLimiterBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatformRateLimiterBundle\Tests\Application;

use ApiPlatformRateLimiterBundle\Tests\Application\Provider\ItemProvider;
use Exception;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * @author Florent TEDESCO
 */
class AppKernel extends Kernel
{
    use MicroKernelTrait {
        registerContainerConfiguration as registerContainerConfigurationTrait;
    }

    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, false);
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->register('api_platform.state.item_provider')
                ->setClass(ItemProvider::class)
            ;
        });

        $this->registerContainerConfigurationTrait($loader);
    }

    /**
     * @param ContainerConfigurator $container
     *
     * @return void
     */
    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('./config/{packages}/*.yaml');
        $container->import('./config/services.yaml');
    }

    /**
     * @param RoutingConfigurator $routes
     * @return void
     */
    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('./config/{routes}/'.$this->environment.'/*.yaml');
        $routes->import('./config/{routes}/*.yaml');
    }

    /**
     * {@inheritDoc}
     */
    public function getProjectDir(): string
    {
        return parent::getProjectDir().'/tests/Application';
    }
}