<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Bus\QueryBusInterface;
use App\Application\Query\QueryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class QueryBus implements QueryBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(QueryInterface $query): mixed
    {
        $envelope = $this->messageBus->dispatch($query);

        return $envelope->last(HandledStamp::class)?->getResult();
    }
}
