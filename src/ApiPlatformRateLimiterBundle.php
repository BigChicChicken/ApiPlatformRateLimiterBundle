<?php

/*
 * This file is part of the ApiPlatformRateLimiterBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatformRateLimiterBundle;

use ApiPlatformRateLimiterBundle\DependencyInjection\ApiPlatformRateLimiterExtension;
use ApiPlatformRateLimiterBundle\DependencyInjection\Compiler\RateLimiterCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Florent TEDESCO
 *
 * @final
 */
class ApiPlatformRateLimiterBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RateLimiterCompilerPass());
    }

    /**
     * {@inheritDoc}
     */
    public function getContainerExtension(): ExtensionInterface
    {
        return new ApiPlatformRateLimiterExtension();
    }
}