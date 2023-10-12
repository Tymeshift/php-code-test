<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Schedule;

interface ScheduleStorageInterface
{
    /**
     * @return array{
     *       id?: int,
     *       start_time?: int,
     *       end_time?: int,
     *       name?: string
     *   }
     */
    public function getById(int $id): array;

    /**
     * @param array<int> $ids
     * @return array<array{
     *       id?: int,
     *       start_time?: int,
     *       end_time?: int,
     *       name?: string
     *   }>
     */
    public function getByIds(array $ids): array;
}
