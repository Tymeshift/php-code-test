<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Task;

use Tymeshift\PhpTest\Components\HttpClientInterface;

class TaskStorage
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->client = $httpClient;
    }

    /**
     * @param int $id
     * @return array<array{
     *     id?: int,
     *     start_time?: int,
     *     duration?: int,
     *     schedule_id?: int
     * }>
     */
    public function getByScheduleId(int $id): array
    {
        return $this->client->request('GET', '/api/tasks/schedule/'.$id);
    }

    /**
     * @param array<int> $ids
     * @return array<array{
     *     id?: int,
     *     start_time?: int,
     *     duration?: int,
     *     schedule_id?: int
     * }>
     */
    public function getByIds(array $ids): array
    {
        $query = http_build_query(['ids' => $ids]);

        return $this->client->request('GET', '/api/tasks?'.$query);
    }

    /**
     * @param int $id
     * @return array{
     *       id?: int,
     *       start_time?: int,
     *       duration?: int,
     *       schedule_id?: int
     *   }
     */
    public function getById(int $id): array
    {
        return $this->client->request('GET', '/api/tasks/'.$id);
    }
}
