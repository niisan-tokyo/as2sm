<?php

require '../vendor/autoload.php';

use React\Promise\Deferred;
use NiisanTokyo\As2sm\As2sm;

$loop = React\EventLoop\Factory::create();

As2sm::wrap(function(){
    $defer = new Deferred();
    $defer->resolve(5);
    $value = (yield $defer);
    
    $defer2 = new Deferred();
    $defer2->resolve($value * $value);
    $val2 = (yield $defer2);
    echo $val2;
});

$loop->run();