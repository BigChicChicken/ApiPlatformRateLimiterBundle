<?php

/*
* This file is part of the ApiPlatformRateLimiterBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

declare(strict_types=1);

namespace ApiPlatformRateLimiterBundle\Tests;

use ApiPlatformRateLimiterBundle\ApiPlatformRateLimiterBundle;
use PHPUnit\Framework\TestCase;

/**
 * @author Florent TEDESCO
 */
class ApiPlatformRateLimiterBundleTest extends TestCase
{
    /**
     * @return void
     */
    public function testClassExist(): void
    {
        $this->assertTrue(class_exists(ApiPlatformRateLimiterBundle::class));
    }

    /**
     * @return void
     */
    public function testExtensionIsLoaded(): void
    {
        $bundle = new ApiPlatformRateLimiterBundle();
        $this->assertNotNull($bundle->getContainerExtension());
    }
}