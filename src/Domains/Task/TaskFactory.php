<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Task;

use Tymeshift\PhpTest\Interfaces\FactoryInterface;

/**
 * @implements FactoryInterface<array{
 *       id?: int,
 *       start_time?: int,
 *       duration?: int,
 *       schedule_id?: int
 *   }, TaskCollection>
 */
final class TaskFactory implements FactoryInterface
{
    public function createEntity(array $data): TaskEntity
    {
        $entity = new TaskEntity();
        if (isset($data['id']) && is_int($data['id'])) {
            $entity->setId($data['id']);
        }

        if (isset($data['start_time']) && is_int($data['start_time'])) {
            $entity->setStartTime($data['start_time']);
        }

        if (isset($data['duration']) && is_int($data['duration'])) {
            $entity->setDuration($data['duration']);
        }

        if (isset($data['schedule_id']) && is_int($data['schedule_id'])) {
            $entity->setScheduleId($data['schedule_id']);
        }

        return $entity;
    }

    public function createCollection(array $data): TaskCollection
    {
        return (new TaskCollection())->createFromArray($data, $this);
    }
}
