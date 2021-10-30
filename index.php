<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use ShoperPL\ShoperDistanceAPI\Management\ShoperDistanceApi;
use ShoperPL\ShoperDistanceAPI\Management\HereApi;
use ShoperPL\ShoperDistanceAPI\Router;
use ShoperPL\ShoperDistanceAPI\Request;
use ShoperPL\ShoperDistanceAPI\Response;
use ShoperPL\ShoperDistanceAPI\Model\Office;
use ShoperPL\ShoperDistanceAPI\Constants\HttpCodes;

Router::delete('/office/([0-9]*)', function (Request $request, Response $response) {
    try {
        (new ShoperDistanceApi())->delete((int)$request->params[0]);
        $response->status(HttpCodes::HTTP_NO_CONTENT)->toJSON('Udało się usunąć nazwę firmy');
    } catch (\Exception $exception) {
        $response->status($exception->getCode())->toJSON($exception->getMessage());
    }
});

Router::get('/office/all', function (Request $request, Response $response) {
    $offices = (new ShoperDistanceApi())->getAll();

    if (!empty($offices)) {
        $response->status(HttpCodes::HTTP_OK)->toJSON($offices);
    } else {
        $response->status(HttpCodes::HTTP_NOT_FOUND)->toJSON(['error' => "Nieznalezione"]);
    };
});

Router::get('/office/([0-9]*)', function (Request $request, Response $response) {
    $office = (new ShoperDistanceApi())->getById((int) $request->params[0]);

    if ($office) {
        $response->status(HttpCodes::HTTP_OK)->toJSON(($office));
    } else {
        $response->status(HttpCodes::HTTP_NOT_FOUND)->toJSON(['error' => "Nieznalezione"]);
    };
});

Router::get('/office-distance/([0-9]*)', function (Request $request, Response $response) {
    try {
        (new HereApi())->calculateDistance((int) $request->params[0], $request->getJSON());
        $response->status(HttpCodes::HTTP_OK)->toJSON('halo');
    } catch (\Exception $exception) {
        $response->status($exception->getCode())->toJSON($exception->getMessage());
    }
});

Router::post('/office', function (Request $request, Response $response) {
    try {
        (new ShoperDistanceApi())->add($request->getJSON());
        $response->status(HttpCodes::HTTP_OK)->toJSON('Udało się dodać nowy adres firmy');
    } catch (\Exception $exception) {
        $response->status($exception->getCode())->toJSON($exception->getMessage());
    }
});

Router::patch('/office', function (Request $request, Response $response) {
    try {
        (new ShoperDistanceApi())->update($request->getJSON());
        $response->status(HttpCodes::HTTP_OK)->toJSON('Udało się zmodyfikować adres firmy');
    } catch (\Exception $exception) {
        $response->status($exception->getCode())->toJSON($exception->getMessage());
    }
});

(new shoperDistanceApi())->run();
