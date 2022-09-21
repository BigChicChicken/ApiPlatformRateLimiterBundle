<?php

/*
 * This file is part of the ApiPlatformRateLimiterBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ApiPlatformRateLimiterBundle\Tests\EventSubscriber;

use ApiPlatformRateLimiterBundle\EventSubscriber\RateLimiterSubscriber;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * @author Florent TEDESCO
 */
class RateLimiterSubscriberTest extends WebTestCase
{
    /**
     * @return void
     */
    public function testClassExist(): void
    {
        $this->assertTrue(class_exists(RateLimiterSubscriber::class));
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testEventUsable(): void
    {
        $kernel = self::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        // Check if the service is declared

        $this->assertTrue($container->has('api_platform.rate_limiter.event.subscriber'));

        $factory = $container->get('api_platform.rate_limiter.event.subscriber');
        $this->assertInstanceOf(RateLimiterSubscriber::class, $factory);

        // Check if event is implemented correctly

        $client = static::createClient();
        $client->catchExceptions(false);

        $client->request('GET', '/books');
        $this->assertResponseStatusCodeSame('200');

        $client->request('GET', '/books');
        $this->assertResponseStatusCodeSame('200');

        $this->expectException(TooManyRequestsHttpException::class);
        $client->request('GET', '/books');
        $this->assertResponseStatusCodeSame('429');
    }
}