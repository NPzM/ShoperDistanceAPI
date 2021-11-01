<?php

declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Tests;

use ShoperPL\ShoperDistanceAPI\Managements\ShoperDistanceApi;
use ShoperPL\ShoperDistanceAPI\Model\Office;
use ShoperPL\ShoperDistanceAPI\Repository\Database;

/**
 * @coversNothing
 */
class ShoperDistanceApiTest extends \PHPUnit\Framework\TestCase
{
    public $database;
    public $shoperDistance;

    protected function setUp(): void
    {
        $this->database = $this->createMock(Database::class);
        $this->shoperDistance = new ShoperDistanceApi($this->database);
    }

    public static function data()
    {
        return [
            [
                [
                    [
                        "id" => 1,
                        "city" => "Testowo",
                        "street" => "Testowa 8",
                        "latitude" => "21.25151515",
                        "longitude" => "11.2151511"
                    ],
                    [
                        "id" => 2,
                        "city" => "Testowsko",
                        "street" => "Test 152",
                        "latitude" => "23.25145615",
                        "longitude" => "12.2154511"
                    ],
                    [
                        "id" => 3,
                        "city" => "Testowice",
                        "street" => "Tessst 52",
                        "latitude" => "33.22355615",
                        "longitude" => "22.2145611"
                    ],
                ]
            ],
        ];
    }

    public static function validFormProvider()
    {
        return [
            //  opis => [
            //    parametry do sprawdzenia
            // ],
            [
                (object)[
                    "city" => "Testowo",
                    "street" => "Testowa 8",
                    "latitude" => "21.25151515",
                    "longitude" => "11.2151511"
                ],
            ],
        ];
    }

    public static function invalidFormProvider()
    {
        return [
            //  opis => [
            //    parametry do sprawdzenia,
            //    oczekiwany kod odpowiedzi,
            //    oczekiwana wiadomość
            // ],
            'brak wymaganego parametru longitude' => [
                (object)[
                    "city" => "Testowo",
                    "street" => "Testowa 8",
                    "latitude" => "21.25151515",
                ],
                'code' => 400,
                'message' => 'Brak parametru longitude w formularzu!',
            ],
            'brak wymaganego parametru street' => [
                (object)[
                    "city" => "Testowo",
                    "latitude" => "21.25151515",
                    "longitude" => "11.2151511",
                ],
                'code' => 400,
                'message' => 'Brak parametru street w formularzu!',
            ],
            'niepoprawna walidacja parametru city' => [
                (object)[
                    "city" => "Testowo123",
                    "street" => "Testowa 8",
                    "latitude" => "21.25151515",
                    "longitude" => "11.2151511",
                ],
                'code' => 400,
                'message' => 'Parametr city może mieć tylko litery!',
            ],
            'niepoprawna walidacja parametru longitude' => [
                (object)[
                    "city" => "Testowo",
                    "street" => "Testowa 8",
                    "latitude" => "21.25151515",
                    "longitude" => "11.test",
                ],
                'code' => 400,
                'message' => 'Parametr longitude jest niepoprawny!',
            ],
        ];
    }

    /**
     * @covers ::getAll
     * @dataProvider data
     */
    public function testGetAll(array $expectedData)
    {
        $offices = [];
        foreach($expectedData as $officeData){
            $offices[] = new Office($officeData['city'], $officeData['street'], $officeData['latitude'], $officeData['longitude'], (int) $officeData['id']);
        }

        $this->database->expects($this->once())->method('all')->willReturn($offices);
        $this->assertSame($offices, $this->shoperDistance->getAll($offices));
    }

    /**
     * @covers ::add
     * @dataProvider validFormProvider
     */
    public function testAdd(object $parameters)
    {
        $this->database->expects($this->once())->method('insert')->with($parameters);
        $this->shoperDistance->add($parameters);
    }

    /**
     * @covers ::add
     * @dataProvider validFormProvider
     */
    public function testInvalidAdd(object $parameters)
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Błąd bazy danych');
        $this->database->expects($this->once())->method('insert')->with($parameters)->willThrowException(new \Exception('Błąd bazy danych'));

        $this->shoperDistance->add($parameters);
    }

    /**
     * @covers ::delete
     */
    public function testDelete()
    {
        $this->database->expects($this->once())->method('deleteById')->with(1);

        $this->shoperDistance->delete(1);
    }

    /**
     * @covers ::delete
     */
    public function testInvalidDelete()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Błąd bazy danych');
        $this->database->expects($this->once())->method('deleteById')->with(1)->willThrowException(new \Exception('Błąd bazy danych'));

        $this->shoperDistance->delete(1);
    }

    /**
     * @covers ::getById
     * @dataProvider data
     */
    public function testGetById(array $expectedData)
    {
        $expectedOffice = new Office($expectedData[0]['city'], $expectedData[0]['street'], $expectedData[0]['latitude'], $expectedData[0]['longitude'], (int) $expectedData[0]['id']);
        $this->database->expects($this->once())->method('getById')->with($expectedData[0]['id'])->willReturn($expectedOffice);

        $this->assertSame($expectedOffice, $this->shoperDistance->getById($expectedData[0]['id']));
    }

    /**
     * @covers ::add
     * @dataProvider invalidFormProvider
     */
    public function testAddInvalidValidation(object $parameters, int $code, string $message): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode($code);
        $this->expectExceptionMessage($message);

        $this->shoperDistance->add($parameters);
    }

    /**
     * @covers ::update
     * @dataProvider validFormProvider
     */
    public function testUpdate(object $parameters)
    {
        $this->database->expects($this->once())->method('update')->with($parameters);

        $this->shoperDistance->update($parameters);
    }

    /**
     * @covers ::update
     * @dataProvider validFormProvider
     */
    public function testInvalidUpdate(object $parameters)
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Błąd bazy danych');
        $this->database->expects($this->once())->method('update')->with($parameters)->willThrowException(new \Exception('Błąd bazy danych'));

        $this->shoperDistance->update($parameters);
    }

    /**
     * @covers ::update
     * @dataProvider invalidFormProvider
     */
    public function testUpdateInvalidValidation(object $parameters, int $code, string $message): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode($code);
        $this->expectExceptionMessage($message);

        $this->shoperDistance->update($parameters);
    }
}
