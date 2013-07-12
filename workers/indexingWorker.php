<?php

$container = require_once __DIR__ . '/../app/bootstrap.php';
$container instanceof SystemContainer;

$consumer = $container->queueConsumer;
$queue = 'indexing';
$callback = callback($container->postsIndexer, 'process');

$reflection = new ReflectionObject($consumer);

$consumer->bindQueue($queue)->addCallback($callback)->consume($queue);