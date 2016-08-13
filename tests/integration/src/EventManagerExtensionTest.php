<?php

namespace Tests\Integration;

use Codeception\Test\Unit;
use Doctrine\Common\EventManager;
use Symfony\Bridge\Doctrine\ContainerAwareEventManager;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class EventManagerExtensionTest extends Unit
{
    /**
     * @expectedException Nette\Utils\AssertionException
     * @expectedExceptionMessage Subscriber 'subscriber' doesn't implement 'Doctrine\Common\EventSubscriber'.
     */
    public function testSubscriberException()
    {
        $this->tester->useConfigFiles(['config/subscriber-exception.neon']);
        $this->tester->getContainer();
    }

    public function testSubscriber()
    {
        $this->tester->useConfigFiles(['config/subscriber.neon']);
        $container = $this->tester->getContainer();

        /* @var $evm EventManager */
        $evm = $container->getByType(EventManager::class);
        $this->assertInstanceOf(ContainerAwareEventManager::class, $evm);

        // Ensure laziness.
        $this->assertFalse($container->isCreated('subscriber'));
        $evm->dispatchEvent('fooEvent');
        $this->assertFalse($container->isCreated('subscriber'));
        $evm->dispatchEvent('barEvent');
        $this->assertTrue($container->isCreated('subscriber'));
    }
}