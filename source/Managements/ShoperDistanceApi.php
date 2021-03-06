<?php
declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Managements;

use ShoperPL\ShoperDistanceAPI\Constants\RequiredParameters;
use ShoperPL\ShoperDistanceAPI\Managements\AbstractManagement;
use ShoperPL\ShoperDistanceAPI\Model\Office;
use ShoperPL\ShoperDistanceAPI\Repository\Database;
use ShoperPL\ShoperDistanceAPI\Request;
use ShoperPL\ShoperDistanceAPI\Traits\ValidatorTrait;

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

    //Opcjonalne parametr na potrzeby testów jednoskowych
    public function __construct($database = null)
    {
        parent::__construct();

        if (is_null($database)) {
            $this->database = new Database();
        } else {
            $this->database = $database;
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
    public function getAll(): array
    {
        $this->logger->info('Pobieranie informacji o biurach');

        $offices = $this->database->all();

        $this->logger->info('Pobranie informacji o biurach zakończyło się sukcesem');

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

        $office = $this->database->getById($id);

        if (is_null($office)) {
            $this->logger->info('Nie znaleziono żadnej informacji');

            return $office;
        };

        $this->logger->info('Pobranie informacji zakończyło się sukcesem');

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