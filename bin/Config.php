<?php

namespace bin;

use Exception;

class Config
{
    protected $envPath;

    public function __construct($envPath = '.env')
    {
        $this->envPath = $envPath;
        $this->loadEnv();
    }

    protected function loadEnv()
    {
        if (!file_exists($this->envPath)) {
            throw new Exception("Environment file not found: $this->envPath");
        }

        $lines = file($this->envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parse the line
            list($key, $value) = explode('=', $line, 2);

            // Remove whitespace around the key and value
            $key = trim($key);
            $value = trim($value);

            // Remove quotes from the value
            $value = trim($value, "\"'");

            // Set the environment variable
            putenv(sprintf('%s=%s', $key, $value));
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}
