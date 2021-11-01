<?php

declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Tests;

use ShoperPL\ShoperDistanceAPI\Clients\HereApiClient;
use ShoperPL\ShoperDistanceAPI\Managements\HereApi;
use ShoperPL\ShoperDistanceAPI\Managements\ShoperDistanceApi;
use ShoperPL\ShoperDistanceAPI\Model\Office;
use ShoperPL\ShoperDistanceAPI\Model\Spot;
use ShoperPL\ShoperDistanceAPI\Repository\Database;

/**
 * @coversNothing
 */
class HereApiTest extends \PHPUnit\Framework\TestCase
{
    public $database;
    public $shoperDistance;

    protected function setUp(): void
    {
        $this->shoperDistance = $this->createMock(ShoperDistanceApi::class);
        $this->hereApiClient = $this->createMock(HereApiClient::class);
        $this->hereApi = new HereApi($this->hereApiClient, $this->shoperDistance);
    }

    public static function validProvider()
    {
        return [
            [
                // [
                //    id testowego biura,
                //    testowe biuro,
                //    parametry spot,
                //    przewidywany wynik
                // ],
                'id' => 1,
                'office' => [
                    "id" => 1,
                    "city" => "Testowo",
                    "street" => "Testowa 8",
                    "latitude" => "21.25151515",
                    "longitude" => "11.2151511"
                ],
                'spot' => (object)[
                    "latitude" => "50.07048609",
                    "longitude" => "19.94635587"
                ],
                'expected' => [
                    "distance" => [
                        "length" => "651 km",
                        "duration" => "6 godzin i 19 minut"
                    ],
                    "office" => [
                        "city" => "Testowo",
                        "street" => "Testowa 8"
                    ],
                    "spot" => "50.07048609,19.94635587"
                ],
            ],
        ];
    }

    public static function invalidValidationProvider()
    {
        return [
            //  opis => [
            //    parametry do sprawdzenia,
            //    oczekiwany kod odpowiedzi,
            //    oczekiwana wiadomość
            // ],
            'brak wymaganego parametru longitude' => [
                (object)[
                    "latitude" => "21.25151515",
                ],
                'code' => 400,
                'message' => 'Brak parametru longitude w formularzu!',
            ],
            'brak wymaganego parametru latitude' => [
                (object)[
                    "longitude" => "11.2151511",
                ],
                'code' => 400,
                'message' => 'Brak parametru latitude w formularzu!',
            ],
            'niepoprawna walidacja parametru longitude' => [
                (object)[
                    "latitude" => "21.test123",
                    "longitude" => "11.2151511",
                ],
                'code' => 400,
                'message' => 'Parametr city może mieć tylko litery!',
            ],
            'niepoprawna walidacja parametru longitude' => [
                (object)[
                    "latitude" => "21.25151515",
                    "longitude" => "11.test123",
                ],
                'code' => 400,
                'message' => 'Parametr longitude jest niepoprawny!',
            ],
        ];
    }

    /**
     * @covers ::calculateDistance
     * @dataProvider validProvider
     */
    public function testCalculateDistance($id, $office, $parameters, $expected): void
    {
        $office = new Office($office['city'], $office['street'], $office['latitude'], $office['longitude'], (int) $office['id']);
        $spot = new Spot($parameters->latitude, $parameters->longitude);

        $this->shoperDistance->expects($this->once())->method('getById')->with($id)->willReturn($office);
        $this->hereApiClient->expects($this->once())->method('getDistance')->with($office, $spot)->willReturn($expected['distance']);

        $information = $this->hereApi->calculateDistance($id, $parameters);

        $this->assertSame($expected, $information);
    }

    /**
     * @covers ::calculateDistance
     * @dataProvider validProvider
     */
    public function testInvalidCalculateDistance($id, $office, $parameters, $expected): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Błąd HereApi');

        $office = new Office($office['city'], $office['street'], $office['latitude'], $office['longitude'], (int) $office['id']);
        $spot = new Spot($parameters->latitude, $parameters->longitude);

        $this->shoperDistance->expects($this->once())->method('getById')->with($id)->willReturn($office);
        $this->hereApiClient->expects($this->once())->method('getDistance')->with($office, $spot)->willThrowException(new \Exception('Błąd HereApi'));

        $information = $this->hereApi->calculateDistance($id, $parameters);

        $this->assertSame($expected, $information);
    }

    /**
     * @covers ::calculateDistance
     * @dataProvider invalidValidationProvider
     */
    public function testCalculateDistanceInvalidValidation(object $parameters, int $code, string $message): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode($code);
        $this->expectExceptionMessage($message);

        $this->hereApi->calculateDistance(1, $parameters);;
    }
}
