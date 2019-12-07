#!/usr/bin/env php
<?php

namespace Building\App;

use Interop\Container\ContainerInterface;

call_user_func(function () : void {
    /** @var ContainerInterface $dic */
    $dic = require __DIR__ . '/../container.php';

    $dic->get('project-checked-in-users')();
});
