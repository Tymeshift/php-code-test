<?php

namespace Tymeshift\PhpTest\Domains\Schedule;

use Tymeshift\PhpTest\Domains\Task\TaskRepository;
use Tymeshift\PhpTest\Exceptions\InvalidCollectionDataProvidedException;
use Tymeshift\PhpTest\Exceptions\StorageDataMissingException;

class ScheduleService
{
    /**
     * @var ScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    public function __construct(
        ScheduleRepository $scheduleRepository,
        TaskRepository $taskRepository
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @return ScheduleItemInterface[]
     * @throws StorageDataMissingException
     * @throws InvalidCollectionDataProvidedException
     */
    public function setTasks(int $scheduleId): array
    {
        $schedule = $this->scheduleRepository->getById($scheduleId);
        $taskCollection = $this->taskRepository->getByScheduleId($scheduleId);
        foreach ($taskCollection as $task) {

        }
    }
}