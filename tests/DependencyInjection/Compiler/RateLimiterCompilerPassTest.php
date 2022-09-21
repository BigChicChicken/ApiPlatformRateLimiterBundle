<?php

/*
 * This file is part of the ApiPlatformRateLimiterBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatformRateLimiterBundle\Tests\DependencyInjection\Compiler;

use ApiPlatformRateLimiterBundle\DependencyInjection\Compiler\RateLimiterCompilerPass;
use PHPUnit\Framework\TestCase;

/**
 * @author Florent TEDESCO
 *
 * @final
 */
class RateLimiterCompilerPassTest extends TestCase
{
    /**
     * @return void
     */
    public function testClassExist(): void
    {
        $this->assertTrue(class_exists(RateLimiterCompilerPass::class));
    }
}