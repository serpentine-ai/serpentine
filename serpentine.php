<?php

/**
 * Serpentine
 * Copyright (C) 2021  Nikita Podvirnyy

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * 
 * Contacts:
 *
 * Email: <suimin.tu.mu.ga.mi@gmail.com>
 * GitHub: https://github.com/KRypt0nn
 * VK:     https://vk.com/technomindlp
 */

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
