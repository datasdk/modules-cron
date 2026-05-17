<?php

namespace Modules\Cron\Services;

class TotemCommandService
{
    /**
     * Tilføj en kommando til filteret
     *
     * @param string|array $commands
     * @return void
     */
    public static function addCommand($commands): void
    {
        $config = config('totem.artisan.command_filter', []);
        
        if (is_array($commands)) {
            $config = array_merge($config, $commands);
        } else {
            $config[] = $commands;
        }
        
        config(['totem.artisan.command_filter' => array_unique($config)]);
    }

    /**
     * Tilføj flere kommandoer på én gang
     *
     * @param array $commands
     * @return void
     */
    public static function addCommands(array $commands): void
    {
        self::addCommand($commands);
    }

    /**
     * Fjern en kommando fra filteret
     *
     * @param string $command
     * @return void
     */
    public static function removeCommand(string $command): void
    {
        $config = config('totem.artisan.command_filter', []);
        $key = array_search($command, $config);
        
        if ($key !== false) {
            unset($config[$key]);
            config(['totem.artisan.command_filter' => array_values($config)]);
        }
    }

    /**
     * Tøm hele filteret
     *
     * @return void
     */
    public static function clearCommands(): void
    {
        config(['totem.artisan.command_filter' => []]);
    }

    /**
     * Hent alle kommandoer i filteret
     *
     * @return array
     */
    public static function getCommands(): array
    {
        return config('totem.artisan.command_filter', []);
    }

    /**
     * Tjek om en kommando er i filteret
     *
     * @param string $command
     * @return bool
     */
    public static function hasCommand(string $command): bool
    {
        return in_array($command, self::getCommands());
    }

    /**
     * Tilføj kommandoer baseret på mønster eller wildcard
     *
     * @param string $pattern
     * @return void
     */
    public static function addCommandsByPattern(string $pattern): void
    {
        // Hent alle artisan kommandoer
        $allCommands = \Artisan::all();
        
        foreach ($allCommands as $command => $instance) {
            if (fnmatch($pattern, $command)) {
                self::addCommand($command);
            }
        }
    }

    /**
     * Tilføj alle kommandoer fra en specifik namespace
     *
     * @param string $namespace
     * @return void
     */
    public static function addCommandsByNamespace(string $namespace): void
    {
        $allCommands = \Artisan::all();
        
        foreach ($allCommands as $command => $instance) {
            if (strpos(get_class($instance), $namespace) === 0) {
                self::addCommand($command);
            }
        }
    }
}