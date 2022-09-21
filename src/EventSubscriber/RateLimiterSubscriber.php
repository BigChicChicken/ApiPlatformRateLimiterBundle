<?php

/*
 * This file is part of the ApiPlatformRateLimiterBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ApiPlatformRateLimiterBundle\EventSubscriber;

use ApiPlatform\Exception\ResourceClassNotFoundException;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use ApiPlatform\Util\RequestAttributesExtractor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\RateLimiter\RateLimiterFactory;

/**
 * Event to control API calls with definitions set under framework.rate_limiter
 *
 * @author Florent TEDESCO
 */
class RateLimiterSubscriber implements EventSubscriberInterface
{
    /**
     * @var array|RateLimiterFactory[]
     */
    private array $limiters;

    /**
     * @var ResourceMetadataCollectionFactoryInterface
     */
    private ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory;

    /**
     * @param array $limiters
     * @param ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory
     */
    public function __construct(array $limiters, ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory)
    {
        $this->limiters = $limiters;
        $this->resourceMetadataCollectionFactory = $resourceMetadataCollectionFactory;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                [ 'onKernelRequest', EventPriorities::PRE_READ ]
            ]
        ];
    }

    /**
     * @param RequestEvent $event
     * @return void
     * @throws ResourceClassNotFoundException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$attributes = RequestAttributesExtractor::extractAttributes($request)) {
            return;
        }

        $resourceMetadata = $this->resourceMetadataCollectionFactory->create($attributes['resource_class']);
        $operation = $resourceMetadata->getOperation($attributes['operation_name']);
        $extra = $operation->getExtraProperties();

        if (!$rateLimiter = $extra['rate_limiter'] ?? null) {
            return;
        }

        $key = sprintf('limiter.%s', $rateLimiter);
        if (in_array($key, $this->limiters)) {
            return;
        }

        $limiter = $this->limiters[$key]->create($request->getClientIp().$request->get('_route'));
        if (false === $limiter->consume()->isAccepted()) {
            throw new TooManyRequestsHttpException(null, 'Too many request, retry later.');
        }
    }
}