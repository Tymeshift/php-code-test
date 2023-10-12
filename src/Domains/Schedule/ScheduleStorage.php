<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Schedule;

use Tymeshift\PhpTest\Components\DatabaseInterface;

final class ScheduleStorage implements ScheduleStorageInterface
{
    private DatabaseInterface $db;

    public function __construct(DatabaseInterface $database)
    {
        $this->db = $database;
    }

    /**
     * @return array{
     *       id?: int,
     *       start_time?: int,
     *       end_time?: int,
     *       name?: string
     *  }
     */
    public function getById(int $id): array
    {
        return $this->db->query('SELECT * FROM schedules WHERE id=:id', ["id" => $id]);
    }

    /**
     * @param array<int> $ids
     * @return array<array{
     *        id?: int,
     *        start_time?: int,
     *        end_time?: int,
     *        name?: string
     *   }>
     */
    public function getByIds(array $ids): array
    {
        return $this->db->query('SELECT * FROM schedules WHERE id in (:ids)', $ids);
    }
}
