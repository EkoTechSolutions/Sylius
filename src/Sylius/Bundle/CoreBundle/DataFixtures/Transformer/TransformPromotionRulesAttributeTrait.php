<?php

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\DataFixtures\Transformer;

use Psr\EventDispatcher\EventDispatcherInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Event\CreateResourceEvent;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\PromotionRuleFactoryInterface;

trait TransformPromotionRulesAttributeTrait
{
    private EventDispatcherInterface $eventDispatcher;

    private function transformRulesAttribute(array $attributes): array
    {
        $rules = [];
        foreach ($attributes['rules'] as $rule) {
            if (\is_array($rule)) {
                /** @var CreateResourceEvent $event */
                $event = $this->eventDispatcher->dispatch(
                    new CreateResourceEvent(PromotionRuleFactoryInterface::class, $rule)
                );

                $rule = $event->getResource();
            }

            $rules[] = $rule;
        }

        $attributes['rules'] = $rules;

        return $attributes;
    }
}
