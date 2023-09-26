<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Task;

use Tymeshift\PhpTest\Components\HttpClientInterface;

class TaskStorage
{
    private $client;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->client = $httpClient;
    }

    public function getByScheduleId(int $id): array
    {
        return $this->client->request(
            'GET',
            'https://tymeshift.com/get-by-schedule-id/' . $id
        );
    }

    public function getByIds(array $ids): array
    {
        // TODO: Implement getByIds() method.
    }
}