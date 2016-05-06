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

Limitations
----

This integration of EventManager is meant to be used only for Doctrine events. For custom events it is recommended to use [Arachne/EventDispatcher](https://github.com/Arachne/EventDispatcher). It is not wrong to use both in the same application. In fact it is the recommended approach. Symfony framework uses it as well.

Also this integration is written to be as simple as possible so there is no support for priorities at the moment. If you need to use priorities for Doctrine events use [Kdyby/Events](https://github.com/Kdyby/Events) instead.
