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

    public function all(): ?object
    {
        $sql = sprintf('SELECT * FROM %s', self::DATABASE_TABLE);

        $result = $this->database->query($sql);

        return $result->num_rows > 0 ? $result : null;
    }

    /**
    * Metoda do usuwania wpisu w bazie na podstawie identyfikatora.
    */
    public function deleteById(int $id): void
    {
        $sql = sprintf('DELETE FROM "%s" WHERE id="%d"', self::DATABASE_TABLE, $id);

        if ($this->database->query($sql) === TRUE) {
            echo "Record deleted successfully";
          } else {
            throw new \Exception('Deleting row error', HttpCodes::HTTP_NOT_FOUND);
        }

        $this->database->close();
    }

    /**
    * Metoda do dodawania wpisu do bazy danych.
    */
    public function insert($json): void
    {
        $sql = sprintf('INSERT INTO %s (`city`, `street`, `latitude`, `longitude`) VALUES
        (%s, %s, %s, %s)', self::DATABASE_TABLE, $city, $street, $latitude, $longitude);

        if ($database->query($sql) === TRUE) {
        echo "New record created successfully";
        } else {
            throw new \Exception("Error: " . $sql . "<br>" . $database->error, HttpCodes::HTTP_NOT_FOUND);
        }

        $database->close();
    }

    /**
    * Metoda
    */
    public function select(int $id): ?array
    {
        $sql = sprintf('SELECT "%s" FROM "%s"', $id, self::DATABASE_TABLE);

        $result = $database->query($sql);

        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
          }
        } else {
          echo "0 results";
        }

        return $results ?? null;
    }

    /**
    * Metoda odpowiedzialna za aktualizację obiektu encji w bazie danych
    */
    public function update($json): void
    {
        $sql = sprintf('UPDATE FROM "%s" WHERE id="%d" SET', self::DATABASE_TABLE, $id);

        if ($database->query($sql) === TRUE) {
          echo "Record updated successfully";
        } else {
          throw new \Exception("Error updating record: " . $database->error, HttpCodes::HTTP_NOT_FOUND);
        }

        $this->database->close();
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