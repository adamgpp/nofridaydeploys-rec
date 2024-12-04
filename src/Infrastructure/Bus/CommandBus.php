<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Bus\CommandBusInterface;
use App\Application\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class CommandBus implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(CommandInterface $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (\Throwable $e) {
            while (true) {
                if ($e instanceof \DomainException) {
                    throw $e;
                }

                if (null === $e->getPrevious()) {
                    throw $e;
                }

                $e = $e->getPrevious();
            }
        }
    }
}
