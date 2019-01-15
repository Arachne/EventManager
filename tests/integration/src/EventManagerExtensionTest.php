<?php

declare(strict_types=1);

namespace Tests\Integration;

use Codeception\Test\Unit;
use Contributte\Codeception\Module\NetteDIModule;
use Doctrine\Common\EventManager;
use Symfony\Bridge\Doctrine\ContainerAwareEventManager;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class EventManagerExtensionTest extends Unit
{
    /**
     * @var NetteDIModule
     */
    protected $tester;

    /**
     * @expectedException \Nette\Utils\AssertionException
     * @expectedExceptionMessage Subscriber "subscriber" doesn't implement "Doctrine\Common\EventSubscriber".
     */
    public function testSubscriberException(): void
    {
        $this->tester->useConfigFiles(['config/subscriber-exception.neon']);
        $this->tester->getContainer();
    }

    public function testSubscriber(): void
    {
        $this->tester->useConfigFiles(['config/subscriber.neon']);
        $container = $this->tester->getContainer();

        /* @var $evm EventManager */
        $evm = $container->getByType(EventManager::class);
        self::assertInstanceOf(ContainerAwareEventManager::class, $evm);

        // Ensure laziness.
        self::assertFalse($container->isCreated('subscriber'));
        $evm->dispatchEvent('fooEvent');
        self::assertFalse($container->isCreated('subscriber'));
        $evm->dispatchEvent('barEvent');
        self::assertTrue($container->isCreated('subscriber'));
    }
}
