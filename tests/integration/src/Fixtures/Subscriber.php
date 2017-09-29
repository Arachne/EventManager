<?php

namespace Tests\Integration\Fixtures;

use Doctrine\Common\EventSubscriber;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class Subscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            'barEvent',
        ];
    }

    public function barEvent(): void
    {
    }
}
