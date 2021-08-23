<?php

namespace Serpentine;

/**
 * Serpentine configs manager
 */
class Config
{
    # Path to config file
    public static string $config_file_path = __DIR__ .'/../config.json';

    public static function get (string $name = null)
    {
        $pref = file_exists (self::$config_file_path) ?
            json_decode (file_get_contents (self::$config_file_path), true) : [];

        if ($name === null)
            return $pref;

        foreach (explode ('.', $name) as $property)
            $pref = $pref === null ? null : ($pref[$property] ?? null);

        return $pref;
    }

    public static function set (string $name = null, $value = null): void
    {
        if ($name === null)
        {
            file_put_contents (self::$config_file_path, json_encode ($value, JSON_PRETTY_PRINT));

            return;
        }
        
        $prefs = file_exists (self::$config_file_path) ?
            json_decode (file_get_contents (self::$config_file_path), true) : [];
        
        $prefs = self::recursiveSet (explode ('.', $name), $prefs, $value);

        file_put_contents (self::$config_file_path, json_encode ($prefs, JSON_PRETTY_PRINT));
    }

    public static function default (string $name, $value): void
    {
        if (self::get ($name) === null)
            self::set ($name, $value);
    }

    public static function defaults (array $defaults): void
    {
        foreach ($defaults as $name => $value)
            if (self::get ($name) === null)
                self::set ($name, $value);
    }

    protected static function recursiveSet (array $names, array $items, $value): array
    {
        $items[current ($names)] = sizeof ($names) > 1 ?
            self::recursiveSet (array_slice ($names, 1), is_array ($i = $items[current ($names)] ?? []) ? $i : [], $value) : $value;

        return $items;
    }
}
