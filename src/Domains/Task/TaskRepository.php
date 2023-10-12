<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Task;

use Tymeshift\PhpTest\Exceptions\InvalidCollectionDataProvidedException;
use Tymeshift\PhpTest\Exceptions\StorageDataMissingException;

final class TaskRepository implements TaskRepositoryInterface
{
    private TaskFactory $factory;

    private TaskStorage $storage;

    public function __construct(TaskStorage $storage, TaskFactory $factory)
    {
        $this->factory = $factory;
        $this->storage = $storage;
    }

    public function getById(int $id): TaskEntity
    {
        $data = $this->storage->getById($id);

        if (empty($data)) {
            throw new StorageDataMissingException('Task not found');
        }

        return $this->factory->createEntity($data);
    }

    public function getByScheduleId(int $scheduleId): TaskCollection
    {
        $data = $this->storage->getByScheduleId($scheduleId);
        if (empty($data)) {
            throw new StorageDataMissingException('Tasks not found for schedule');
        }

        return $this->factory->createCollection($data);
    }

    /**
     * @param array<int> $ids
     * @throws InvalidCollectionDataProvidedException
     */
    public function getByIds(array $ids): TaskCollection
    {
        $data = $this->storage->getByIds($ids);

        return $this->factory->createCollection($data);
    }
}
