<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Schedule;

use Tymeshift\PhpTest\Interfaces\FactoryInterface;

/**
 * @implements  FactoryInterface<array{
 *       id?: int,
 *       start_time?: int,
 *       end_time?: int,
 *       name?: string
 *   }, ScheduleCollection>
 */
final class ScheduleFactory implements FactoryInterface
{
    public function createEntity(array $data): ScheduleEntity
    {
        $entity = new ScheduleEntity();
        if (isset($data['id']) && is_int($data['id'])) {
            $entity->setId($data['id']);
        }

        if (isset($data['start_time']) && is_int($data['start_time'])) {
            $entity->setStartTime((new \DateTime())->setTimestamp($data['start_time']));
        }

        if (isset($data['end_time']) && is_int($data['end_time'])) {
            $entity->setEndTime((new \DateTime())->setTimestamp($data['end_time']));
        }

        if (isset($data['name']) && is_string($data['name'])) {
            $entity->setName($data['name']);
        }

        return $entity;
    }

    public function createCollection(array $data): ScheduleCollection
    {
        return (new ScheduleCollection())->createFromArray($data, $this);
    }
}
