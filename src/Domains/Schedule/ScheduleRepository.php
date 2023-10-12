<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Schedule;

use Tymeshift\PhpTest\Exceptions\StorageDataMissingException;
use Tymeshift\PhpTest\Interfaces\FactoryInterface;

final class ScheduleRepository implements ScheduleRepositoryInterface
{
    private ScheduleStorageInterface $storage;

    private ScheduleFactory $factory;

    public function __construct(ScheduleStorage $storage, ScheduleFactory $factory)
    {
        $this->storage = $storage;
        $this->factory = $factory;
    }

    public function getById(int $id): ScheduleEntity
    {
        $data = $this->storage->getById($id);

        if (empty($data)) {
            throw new StorageDataMissingException('Schedule not found');
        }

        return $this->factory->createEntity($data);
    }

    public function getByIds(array $ids): ScheduleCollection
    {
        $data = $this->storage->getByIds($ids);

        return $this->factory->createCollection($data);
    }
}
