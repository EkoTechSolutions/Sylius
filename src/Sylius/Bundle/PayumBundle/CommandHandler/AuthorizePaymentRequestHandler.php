<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\PayumBundle\CommandHandler;

use Payum\Core\Security\TokenAggregateInterface;
use Sylius\Bundle\PayumBundle\Command\AuthorizePaymentRequest;
use Sylius\Bundle\PayumBundle\Factory\AuthorizeRequestFactoryInterface;
use Sylius\Bundle\PayumBundle\PaymentRequest\Factory\PayumTokenFactoryInterface;
use Sylius\Bundle\PayumBundle\PaymentRequest\Processor\AfterTokenizedRequestProcessorInterface;
use Sylius\Bundle\PayumBundle\PaymentRequest\Processor\RequestProcessorInterface;
use Sylius\Bundle\PayumBundle\PaymentRequest\Provider\PaymentRequestProviderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final class AuthorizePaymentRequestHandler implements MessageHandlerInterface
{
    public function __construct(
        private PaymentRequestProviderInterface $paymentRequestProvider,
        private PayumTokenFactoryInterface $payumTokenFactory,
        private RequestProcessorInterface $requestProcessor,
        private AuthorizeRequestFactoryInterface $factory,
        private AfterTokenizedRequestProcessorInterface $afterTokenizedRequestProcessor,
    ) {
    }

    public function __invoke(AuthorizePaymentRequest $command): void
    {
        $paymentRequest = $this->paymentRequestProvider->provideFromHash($command->getHash());
        Assert::notNull($paymentRequest);

        $token = $this->payumTokenFactory->createNew($paymentRequest);

        $request = $this->factory->createNewWithToken($token);

        $token = $request->getToken();
        Assert::notNull($token);

        $this->requestProcessor->process($paymentRequest, $request, $token->getGatewayName());

        $this->afterTokenizedRequestProcessor->process($paymentRequest, $token);
    }
}
