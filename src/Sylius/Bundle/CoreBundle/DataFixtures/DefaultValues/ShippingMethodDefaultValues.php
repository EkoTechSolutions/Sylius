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

namespace Sylius\Bundle\CoreBundle\DataFixtures\DefaultValues;

use Faker\Generator;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\ChannelFactoryInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\ZoneFactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ShippingMethodDefaultValues implements ShippingMethodDefaultValuesInterface
{
    public function __construct(
        private RepositoryInterface $channelRepository,
    ) {
    }

    public function getDefaults(Generator $faker): array
    {
        return [
            'code' => null,
            'name' => (string) $faker->words(3, true),
            'description' => $faker->sentence(),
            'zone' => null,
            'tax_category' => null,
            'category' => null,
            'calculator' => null,
            'channels' => $this->channelRepository->findAll(),
            'archived_at' => null,
            'enabled' => true,
        ];
    }
}
