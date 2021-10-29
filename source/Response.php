<?php
declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI;

class Response
{
    private $status = 404;

    public function status(int $code)
    {
        $this->status = $code;
        return $this;
    }

    public function toJSON($data = []): void
    {
        http_response_code($this->status);
        header('Content-Type: application/json');

        echo is_array($data) ? json_encode($data, JSON_PRETTY_PRINT) : json_encode([$data], JSON_PRETTY_PRINT);
    }
}