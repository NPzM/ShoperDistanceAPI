<?php

declare(strict_types=1);

namespace HomePL\Tests\OdinProxy\Controller;

use ShoperPL\ShoperDistanceAPI\Managements\ShoperDistanceApi;
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
                ]
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
     * @dataProvider invalidFormProvider
     */
    public function testAddValidation(object $parameters, int $code, string $message): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode($code);
        $this->expectExceptionMessage($message);

        $this->shoperDistance->add($parameters);
    }

    /**
     * @covers ::add
     * @dataProvider validFormProvider
     */
    public function testUpdate(object $parameters)
    {
        $this->database->expects($this->once())->method('update')->with($parameters);

        $this->shoperDistance->update($parameters);
    }

    /**
     * @covers ::update
     * @dataProvider invalidFormProvider
     */
    public function testUpdateValidation(object $parameters, int $code, string $message): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode($code);
        $this->expectExceptionMessage($message);

        $this->shoperDistance->update($parameters);
    }
}
