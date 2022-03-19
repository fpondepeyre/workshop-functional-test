<?php

namespace App\EntityListener;

use App\Entity\Product;
use App\Message\SendEmailMessage;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;

class ProductEntityListener
{
    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function prePersist(Product $product, LifecycleEventArgs $event)
    {
        $this->bus->dispatch(new SendEmailMessage('Hey a new message !'));
    }
}
