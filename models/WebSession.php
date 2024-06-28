<?php

namespace Models;

use bin\Database;
use Exception;

class WebSession
{
    protected Database $db;
    protected string $key;
    public function __construct(Database $db)
    {
        $this->db = $db;
        $cfg = $_ENV['cookie_name'];
        if (!isset($_COOKIE[$cfg])) {
            $this->key = $this->create([]);
            setcookie($cfg, $this->key);
        } else {
            $this->key = $_COOKIE[$cfg];
            if (!$this->isSessionExists($this->key)) {
                $this->key = $this->create([]);
                setcookie($cfg, $this->key);
            }
        }
    }

    protected function isSessionExists($key): bool
    {
        $this->db->query('select * from websession where code = :code');
        $this->db->bind(':code', $key);
        return $this->db->single() != null;
    }

    protected function create(array $content): string
    {
        $key = $this->KeyGen(10);
        $cnt = $content != null && count($content) > 0 ? $this->arrayToJson($content) : $this->arrayToJson([]);
        $this->db->query('insert into websession values(:code,:content,:create_time,:update_time)');
        $this->db->bind(':code', $key);
        $this->db->bind(':content', $cnt);
        $this->db->bind(':create_time', date('Y-m-d H:i:s'));
        $this->db->bind(':update_time', null);
        $this->db->execute();

        return $key;
    }

    public function getContent($name): mixed
    {
        $this->db->query('select * from websession where code = :code');
        $this->db->bind(':code', $this->key);
        $result = $this->db->single();

        if ($result != null && count($result) > 0) {
            $arrContent = $this->jsonToArray($result['content']);
            return $arrContent[$name];
        }

        return null;
    }

    public function appendContent(string $name, mixed $content): void
    {
        $this->db->query('select * from websession where code = :code');
        $this->db->bind(':code', $this->key);
        $result = $this->db->single();

        if ($result != null && count($result) > 0) {
            $arrContent = $this->jsonToArray($result['content']);
            $arrContent[$name] = $content;
            $this->db->query('update websession set content = :content where code = :code');
            $this->db->bind(':content', $this->arrayToJson($arrContent));
            $this->db->bind(':code', $this->key);
            $this->db->execute();
        }
    }

    public function removeContent(string $name): void
    {
        $this->db->query('select * from websession where code = :code');
        $this->db->bind(':code', $this->key);
        $result = $this->db->single();

        if ($result != null && count($result) > 0) {
            $arrContent = $this->jsonToArray($result['content']);
            unset($arrContent[$name]);
            $this->db->query('update websession set content = :content where code = :code');
            $this->db->bind(':content', $this->arrayToJson($arrContent));
            $this->db->bind(':code', $this->key);
            $this->db->execute();
        }
    }

    private function jsonToArray(string $jsonString): array
    {
        // Decode the JSON string into a PHP array
        $array = json_decode($jsonString, true);

        // Check if json_decode encountered an error
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON string: ' . json_last_error_msg());
        }

        return $array;
    }

    private function arrayToJson(array $array): string
    {
        // Encode the PHP array into a JSON string
        $jsonString = json_encode($array);

        // Check if json_encode encountered an error
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error encoding array to JSON: ' . json_last_error_msg());
        }

        return $jsonString;
    }

    private function keyGen($length): string
    {
        // Define the characters to use in the key
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        // Generate a random key
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
