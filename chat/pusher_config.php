<?php
require_once '../vendor/autoload.php';

use Pusher\Pusher;

$options = [
    'cluster' => 'us2',
    'useTLS' => true
];

$pusher = new Pusher(
    '0469c2ac8ae0b818938a',
    '6255749942b37fe237a3',
    '2034009',
    $options
);
