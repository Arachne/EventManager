Documentation
====

This package integrates EventManager from [doctrine/common](https://github.com/doctrine/common) to Nette Framework.

Installation
----

The best way to install Arachne/EventManager is using [Composer](http://getcomposer.org/).

```sh
$ composer require arachne/event-manager
```

Now add these extensions to your config.neon.

```yml
extensions:
    arachne.containeradapter: Arachne/ContainerAdapter/DI/ContainerAdapterExtension
    arachne.eventmanager: Arachne/EventManager/DI/EventManagerExtension
```

Subscribers
----

To add register an event subscriber, add it to your config.neon.

```
services:
    subscriber:
        class: App\AdminModule\Event\Subscriber
        tags:
            - arachne.eventManager.subscriber
```

You can also simplify it using the DecoratorExtension from Nette.

```
decorator:
    Doctrine\Common\EventSubscriber:
        tags:
            - arachne.eventManager.subscriber

services:
    subscriber: App\AdminModule\Event\Subscriber
```
