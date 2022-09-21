<?php

/*
 * This file is part of the ApiPlatformRateLimiterBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatformRateLimiterBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Share all definitions under framework.rate_limiter to RateLimiterSubscriber
 *
 * @author Florent TEDESCO
 *
 * @final
 */
class RateLimiterCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $limiters = [];

        foreach ($container->getDefinitions() as $key => $definition) {
            if ($definition instanceof ChildDefinition && $definition->getParent() === 'limiter') {
                $limiters[$key] = $definition;
            }
        }

        $container->getDefinition('api_platform.rate_limiter.event.subscriber')
            ->setArguments([
                '$limiters' => $limiters,
                '$resourceMetadataCollectionFactory' => new Reference('api_platform.metadata.resource.metadata_collection_factory')
            ])
        ;
    }
}