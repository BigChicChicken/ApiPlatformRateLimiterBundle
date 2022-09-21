<?php

/*
 * This file is part of the ApiPlatformRateLimiterBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatformRateLimiterBundle\Tests\DependencyInjection;

use ApiPlatformRateLimiterBundle\DependencyInjection\ApiPlatformRateLimiterExtension;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Florent TEDESCO
 */
class ApiPlatformRateLimiterExtensionTest extends TestCase
{
    /**
     * @return void
     */
    public function testClassExist(): void
    {
        $this->assertTrue(class_exists(ApiPlatformRateLimiterExtension::class));
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testLoader(): void
    {
        $container = new ContainerBuilder();
        $loader = new ApiPlatformRateLimiterExtension();
        $loader->load([], $container);

        $resources = array_map(function(FileResource $fileResource) {
            return $fileResource->getResource();
        }, $container->getResources());

        $path = realpath(dirname(__DIR__, 2).'/config/services.yaml');

        $this->assertTrue(in_array($path, $resources));
    }
}