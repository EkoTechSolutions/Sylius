<?php

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\DataFixtures\Util;

use Psr\EventDispatcher\EventDispatcherInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Event\RandomOrCreateResourceEvent;
use Sylius\Bundle\CoreBundle\DataFixtures\Event\ResourceEventInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\CountryFactoryInterface;
use Sylius\Component\Addressing\Model\CountryInterface;
use Zenstruck\Foundry\Proxy;

trait RandomOrCreateCountryTrait
{
    private EventDispatcherInterface $eventDispatcher;

    private function randomOrCreateCountry(): Proxy|CountryInterface
    {
        /** @var ResourceEventInterface $event */
        $event = $this->eventDispatcher->dispatch(
            new RandomOrCreateResourceEvent(CountryFactoryInterface::class)
        );

        /** @var CountryInterface $country */
        $country = $event->getResource();

        return $country;
    }
}
