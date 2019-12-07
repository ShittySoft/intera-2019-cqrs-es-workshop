#!/usr/bin/env php
<?php

namespace Building\App;

use Interop\Container\ContainerInterface;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream\StreamName;

call_user_func(function () : void {
    /** @var ContainerInterface $dic */
    $dic = require __DIR__ . '/../container.php';

    $eventStore = $dic->get(EventStore::class);

    $events = $eventStore->loadEventsByMetadataFrom(
        new StreamName('event_stream'),
        [ /* ... */ ]
    );

    // ???
});
