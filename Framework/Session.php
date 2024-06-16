<?php

namespace Framework;

class Session
{
    public static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function clear(string $key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function clearAll(): void
    {
        session_unset();
        session_destroy();
    }

    public static function setFlashMessage(string $key, string $message): void
    {
        self::set("flash_" . $key, $message);
    }

    public static function getFlashMessage(
        string $key,
        mixed $default = null
    ): string|null {
        $message = self::get("flash_" . $key, $default);
        self::clear("flash_" . $key);
        return $message;
    }
}
