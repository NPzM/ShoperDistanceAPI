<?php

declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI;

use ShoperPL\ShoperDistanceAPI\Constants\HttpCodes;

/**
* Klasa do połączenia się z bazą danych oraz operacji na niej
*/
class Database
{
    const DATABASE_TABLE = 'office';

    /**
     * @var mysqli
     */
    private $database;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $username;

    public function __construct()
    {
        $this->host = "db";
        $this->username = 'shoper_distance_api';
        $this->password = 'Ad2mrZEv';
        $this->name = 'database_shoper_distance_api';

        $this->connect();
    }

    /**
    * Metoda do pobierania wszystkich adresów firmy.
    */
    public function all(): ?object
    {
        $sql = sprintf('SELECT * FROM %s', self::DATABASE_TABLE);

        $result = $this->database->query($sql);

        return $result->num_rows > 0 ? $result : null;
    }

    /**
    * Metoda do pobierania adresu firmy na podstawie ID.
    */
    public function getById(int $id): ?object
    {
        $sql = sprintf('SELECT * FROM %s WHERE id=%d', self::DATABASE_TABLE, $id);

        $result = $this->database->query($sql);

        return $result->num_rows > 0 ? $result : null;
    }

    /**
    * Metoda do usuwania wpisu z bazy danych na podstawie identyfikatora.
    */
    public function deleteById(int $id): void
    {
        $sql = sprintf('DELETE FROM %s WHERE id=%d', self::DATABASE_TABLE, $id);

        if ($this->database->query($sql) === TRUE) {
        } else {
            throw new \Exception("Error: " . $sql . "<br>" . $this->database->error, HttpCodes::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    /**
    * Metoda do dodawania wpisu do bazy danych.
    */
    public function insert($parameters): void
    {
        $sql = sprintf('INSERT INTO %s (street, city, latitude, longitude) VALUES ("%s", "%s", "%s", "%s")',
        self::DATABASE_TABLE, $parameters->street, $parameters->city, $parameters->latitude, $parameters->longitude);

        if ($this->database->query($sql) === TRUE) {
        } else {
            throw new \Exception("Error: " . $sql . "<br>" . $this->database->error, HttpCodes::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    /**
    * Metoda odpowiedzialna za aktualizację wpisu w bazie danych.
    */
    public function update($parameters): void
    {
        $sql = sprintf("UPDATE %s SET street='%s', city='%s', latitude='%s', longitude='%s' WHERE id=%s",
        self::DATABASE_TABLE, $parameters->street, $parameters->city, $parameters->latitude, $parameters->longitude, $parameters->id);

        if ($this->database->query($sql) === TRUE) {
        } else {
            throw new \Exception("Error: " . $sql . "<br>" . $this->database->error, HttpCodes::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    public function __destruct() {
        $this->disconnect();
    }

    private function connect(): void
    {
        $this->database = new \mysqli($this->host, $this->username, $this->password, $this->name);

        if ($this->database->connect_error) {
            throw new \Exception("Connection failed: " . $this->database->connect_error, HttpCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function disconnect(): void
    {
        if (isset($this->database)) {
            $this->database->close();
        }
    }
}