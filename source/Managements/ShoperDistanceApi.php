<?php
declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Managements;

use ShoperPL\ShoperDistanceAPI\Constants\RequiredParameters;
use ShoperPL\ShoperDistanceAPI\Managements\AbstractManagement;
use ShoperPL\ShoperDistanceAPI\Model\Office;
use ShoperPL\ShoperDistanceAPI\Request;
use ShoperPL\ShoperDistanceAPI\Traits\ValidatorTrait;
use ShoperPL\ShoperDistanceAPI\Repository\Database;

/**
* Klasa do zarządania ShoperDistanceApi.
*/
class ShoperDistanceApi extends AbstractManagement
{
    use ValidatorTrait;

    /**
    * @var Database
    */
    protected $database;

    public function __construct($database = null)
    {
        parent::__construct();

        if (is_null($database)) {
            $this->database = new Database();
        }
    }

    /**
     * Dodawanie biura do bazy danych.
     *
     * @param object $parameters parametry
     */
    public function add(object $parameters): void
    {
        $this->logger->info('Rozpoczęcie dodawania nowego adresu firmy');

        try {
            $this->checkRequiredParameters($parameters, RequiredParameters::OFFICE);
            $this->validateParameters($parameters);
            $this->database->insert($parameters);
        } catch (\Exception $exception) {
            $this->logger->error('Dodanie nowego adresu firmy zakończyło się błędem');

            throw $exception;
        }

        $this->logger->info('Dodanie nowego adresu firmy zakończyło się pomyślnie');
    }

    /**
     * Usuwanie biura na podstawie identyfikatora.
     *
     * @param int $id indetyfikator biura
     */
    public function delete(int $id): void
    {
        $this->logger->info('Rozpoczęcie usuwania adresu firmy');

        try {
            $this->database->deleteById($id);
        } catch (\Exception $exception) {
            $this->logger->error('Usuwanie adresu firmy zakończyło się błędem');

            throw $exception;
        }

        $this->logger->info('Usuwanie adresu firmy zakończyło się pomyślnie');
    }

    /**
     * Pobieranie wszystkich biur Shoper'a w bazie danych
     *
     * @return array/null offices wszystkie biura
     */
    public function getAll(): ?array
    {
        $this->logger->info('Pobieranie informacji o biurach');

        $offices = [];
        foreach($this->database->all() as $result){
            $offices[] = new Office($result['city'], $result['street'], $result['latitude'], $result['longitude'], (int) $result['id']);
        }

        $this->logger->info('Pobranie informacji zakończyło się sukcesem');

        return $offices;
    }

    /**
     * Pobieranie biura na podstawie identyfikatora.
     *
     * @param int $id indetyfikator biura
     *
     * @return Office/null office biuro
     */
    public function getById(int $id): ?Office
    {
        $this->logger->info('Pobieranie informacji na temat biura');

        $result = $this->database->getById($id);

        if (is_null($result)) {
            $this->logger->info('Nie znaleziono żadnej informacji');

            return $result;
        };

        foreach ($result as $row) {
            $office = new Office($row['city'], $row['street'], $row['latitude'], $row['longitude'], (int) $row['id']);
        }

        $this->logger->info('Pobranie informacji zakończyło się sukcesem');

        if (!$office) {
            throw new \Exception("Nie znaleziono biura", ApiConstants::HTTP_NOT_FOUND);
        }

        return $office;
    }

    /**
     * Aktualizacja lokalizacji biura.
     *
     * @param object $parameters parametry
     */
    public function update(object $parameters): void
    {
        $this->logger->info('Rozpoczęcie aktualizacji adresu firmy');

        try {
            $this->checkRequiredParameters($parameters, RequiredParameters::OFFICE);
            $this->validateParameters($parameters);
            $this->database->update($parameters);
        } catch (\Exception $exception) {
            $this->logger->error('Aktualizacja adresu firmy zakończyło się błędem');

            throw $exception;
        }

        $this->logger->info('Aktualizacja adresu firmy zakończyło się pomyślnie');
    }
}