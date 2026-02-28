<?php

return [
    [
        'id_fnord' => 1,
        'bar' => 'hello',
        'baz' => 'world',
    ],
    [
        'id_fnord' => 2,
        'bar' => null,
        'baz' => null,
    ],
    [
        'id_fnord' => 3,
        'bar' => str_repeat('a', 255), // max-length bar
        'baz' => 'short baz',
    ],
];
