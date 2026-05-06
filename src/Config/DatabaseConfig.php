<?php

declare(strict_types=1);

namespace App\Library\Config;

class DatabaseConfig{
    private string $db_host;
    private string $db_username;
    private string $db_password;
    private string $database;


    public function __construct(
        string $db_host,
        string $db_username,
        string $db_password,
        string $database
    ) {
        $this->db_host = $host;
        $this->db_username = $username;
        $this->db_password = $password;
        $this->db_database = $database;
    }

    public function getHost(): string
    {
        return $this->db_host;
    }

    public function getUsername(): string
    {
        return $this->db_username;
    }

    public function getPassword(): string
    {
        return $this->db_password;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }
}