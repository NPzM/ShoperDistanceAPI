<?php
declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Managements;

use ShoperPL\ShoperDistanceAPI\Managements\AbstractManagement;
use ShoperPL\ShoperDistanceAPI\Model\Office;
use ShoperPL\ShoperDistanceAPI\Request;

class ShoperDistanceApi extends AbstractManagement
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Dodawanie biura do bazy danych
     */
    public function add(object $parameters)
    {
        $this->logger->info('Rozpoczęcie dodawania nowego adresu firmy');

        // $validate = $this->validate($request->getJSON());
        // if ($validate['valid'] === false) {
        //     return $validate;
        // };

        // $newOffice = new Office($parameters['street'], $parameters['city'], $latitude['street'], $parameters['longitude']);

        // var_dump($newOffice);

        try {
            $this->database->insert($parameters);
        } catch (\Exception $exception) {
            $this->logger->error('Dodanie nowego adresu firmy zakończyło się błędem');

            throw $exception;
        }

        $this->logger->info('Dodanie nowego adresu firmy zakończyło się pomyślnie');
    }

    /**
     * Pobieranie wszystkich biur Shoper'a w bazie danych
     */
    public function delete(int $id)
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
     * Pobieranie biura na podstawie identyfikatora
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
     * Aktualizacja lokalizacji biura
     */
    public function update(object $parameters)
    {
        $this->logger->info('Rozpoczęcie aktualizacji adresu firmy');

        // $validate = $this->validate($request->getJSON());
        // if ($validate['valid'] === false) {
        //     return $validate;
        // };

        // $newOffice = new Office($parameters['street'], $parameters['city'], $latitude['street'], $parameters['longitude']);

        // var_dump($newOffice);

        try {
            $this->database->update($parameters);
        } catch (\Exception $exception) {
            $this->logger->error('Aktualizacja adresu firmy zakończyło się błędem');

            throw $exception;
        }

        $this->logger->info('Aktualizacja adresu firmy zakończyło się pomyślnie');
    }

    private function validate(object $object)
    {
        $notValid = [];
        if(!isset($object->street)) {
            $notValid[] = 'Brak "street" w body';
        };

        if (!empty($notValid)) {
            $this->logger->error('Brak parametrów', $notValid);

            return ['valid' => false, 'notValid' => $notValid];
        }

        return ['valid' => true, 'notValid' =>$notValid];
    }
}