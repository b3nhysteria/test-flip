<?php

$variables = [
    'DB_HOST' => 'localhost',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => 'B@r07271',
    'DB_NAME' => 'dump',
    'BASE_FLIP' => 'https://nextar.flip.id',
    'TOKEN' => 'HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41'
];
foreach ($variables as $key => $value) {
    putenv("$key=$value");
}
