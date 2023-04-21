<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\DataFixtures\Transformer;

use Psr\EventDispatcher\EventDispatcherInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Event\FindOrCreateZoneMemberByQueryStringEvent;

final class ZoneTransformer implements ZoneTransformerInterface
{
    use TransformNameToCodeAttributeTrait;

    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function transform(array $attributes): array
    {
        $attributes = $this->transformNameToCodeAttribute($attributes);

        return $this->transformZoneMemberAttribute($attributes);
    }

    private function transformZoneMemberAttribute(array $attributes): array
    {
        $members = [];

        foreach ($attributes['members'] as $member) {
            if (\is_string($member)) {
                $event = new FindOrCreateZoneMemberByQueryStringEvent($member);
                $this->eventDispatcher->dispatch($event);

                $member = $event->getZoneMember();
            }

            $members[] = $member;
        }

        $attributes['members'] = $members;

        return $attributes;
    }
}
