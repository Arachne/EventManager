<?php

/**
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\EventManager\DI;

use Nette\DI\CompilerExtension;
use ReflectionClass;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class EventManagerExtension extends CompilerExtension
{
    const TAG_SUBSCRIBER = 'arachne.eventManager.subscriber';

    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('eventManager'))
            ->setClass('Symfony\Bridge\Doctrine\ContainerAwareEventManager');
    }

    public function beforeCompile()
    {
        $builder = $this->getContainerBuilder();

        $subscribers = $builder->findByTag(self::TAG_SUBSCRIBER);
        if ($subscribers) {
            $evm = $builder->getDefinition($this->prefix('eventManager'));
            foreach ($subscribers as $name => $attributes) {
                $subscriber = $builder->getDefinition($name);
                $evm->addSetup('?->addEventListener(?, ?)', [
                    '@self',
                    (new ReflectionClass($subscriber->getClass()))->newInstanceWithoutConstructor()->getSubscribedEvents(),
                    $name, // Intentionally without @ for laziness.
                ]);
            }
        }
    }
}
