<?php

namespace Tymeshift\PhpTest\Domains\Schedule;

use Tymeshift\PhpTest\Exceptions\StorageDataMissingException;
use Tymeshift\PhpTest\Interfaces\EntityInterface;
use Tymeshift\PhpTest\Interfaces\FactoryInterface;

class ScheduleRepository
{
    private const SCHEDULE_DOES_NOT_EXIST = 'Schedule with id %s does not exist.';

    /**
     * @var ScheduleStorage
     */
    private $storage;

    /**
     * @var ScheduleFactory
     */
    private $factory;

    public function __construct(ScheduleStorage $storage, ScheduleFactory $factory)
    {
        $this->storage = $storage;
        $this->factory = $factory;
    }

    /**
     * @throws StorageDataMissingException
     */
    public function getById(int $id): EntityInterface
    {
        $data = $this->storage->getById($id);
        if (empty($data)) {
            throw new StorageDataMissingException(
                sprintf(self::SCHEDULE_DOES_NOT_EXIST, $id)
            );
        }
        return $this->factory->createEntity($data);
    }
}