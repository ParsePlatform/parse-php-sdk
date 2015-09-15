<?php

namespace Parse;

/**
 * ParseSessionStorage - Uses PHP session support for persistent storage.
 *
 * @author Fosco Marotto <fjm@fb.com>
 */
class ParseSessionStorage implements ParseStorageInterface
{
    /**
     * Parse will store its values in a specific key.
     *
     * @var string
     */
    private $storageKey = 'parseData';

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            throw new ParseException(
                'PHP session_start() must be called first.'
            );
        }
        if (!isset($_SESSION[$this->storageKey])) {
            $_SESSION[$this->storageKey] = [];
        }
    }

    /**
     * {inheritDoc}
     */
    public function set($key, $value)
    {
        $_SESSION[$this->storageKey][$key] = $value;
    }

    /**
     * {inheritDoc}
     */
    public function remove($key)
    {
        unset($_SESSION[$this->storageKey][$key]);
    }

    /**
     * {inheritDoc}
     */
    public function get($key)
    {
        if (isset($_SESSION[$this->storageKey][$key])) {
            return $_SESSION[$this->storageKey][$key];
        }

        return;
    }

    /**
     * {inheritDoc}
     */
    public function clear()
    {
        $_SESSION[$this->storageKey] = [];
    }

    /**
     * {inheritDoc}
     */
    public function save()
    {
        // No action required.    PHP handles persistence for $_SESSION.
        return;
    }

    /**
     * {inheritDoc}
     */
    public function getKeys()
    {
        return array_keys($_SESSION[$this->storageKey]);
    }

    /**
     * {inheritDoc}
     */
    public function getAll()
    {
        return $_SESSION[$this->storageKey];
    }
}
