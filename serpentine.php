<?php

namespace Serpentine;

spl_autoload_register (function (string $class)
{
    if (strlen ($class) > 12 && file_exists ($class = __DIR__ .'/src/'. str_replace ('\\', '/', substr ($class, 11)) .'.php'))
        require $class;
});

Config::defaults ([
    'language' => 'en', // bot language
    'randomForest' => [
        'minThreshold' => 0.35, // min part of samples in one tree (1000 samples * 0.35 = 350 random samples per tree)
        'maxThreshold' => 0.9,  // max part of samples in one tree (1000 samples * 0.9  = 900 random samples per tree)
        'forestSize'   => null  // auto bruteforced size (1 + [sqrt (samplesAmount ^ 1.4)])
    ]
]);
