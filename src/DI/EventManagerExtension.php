<?php

namespace Arachne\EventManager\DI;

use Doctrine\Common\EventSubscriber;
use Nette\DI\CompilerExtension;
use Nette\Utils\AssertionException;
use ReflectionClass;
use Symfony\Bridge\Doctrine\ContainerAwareEventManager;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class EventManagerExtension extends CompilerExtension
{
    /**
     * Subscribers with this tag are added to the event manager.
     */
    const TAG_SUBSCRIBER = 'arachne.eventManager.subscriber';

    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('eventManager'))
            ->setClass(ContainerAwareEventManager::class);
    }

    public function beforeCompile()
    {
        $builder = $this->getContainerBuilder();

        // Process event subscribers.
        $evm = $builder->getDefinition($this->prefix('eventManager'));
        foreach ($builder->findByTag(self::TAG_SUBSCRIBER) as $name => $attributes) {
            $class = $builder->getDefinition($name)->getClass();

            if (!is_subclass_of($class, EventSubscriber::class)) {
                throw new AssertionException(
                    sprintf(
                        'Subscriber "%s" doesn\'t implement "%s".',
                        $name,
                        EventSubscriber::class
                    )
                );
            }

            $evm->addSetup('?->addEventListener(?, ?)', [
                '@self',
                (new ReflectionClass($class))->newInstanceWithoutConstructor()->getSubscribedEvents(),
                $name, // Intentionally without @ for laziness.
            ]);
        }
    }
}
