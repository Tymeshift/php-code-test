<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Schedule;

use Tymeshift\PhpTest\Domains\Task\TaskRepositoryInterface;

final class ScheduleService implements ScheduleServiceInterface
{
    private ScheduleRepositoryInterface $scheduleRepository;

    private TaskRepositoryInterface $taskRepository;
    private TaskToScheduleItemMapper $taskToScheduleItemMapper;

    public function __construct(
        ScheduleRepositoryInterface $scheduleRepository,
        TaskRepositoryInterface $taskRepository,
        TaskToScheduleItemMapper $taskToScheduleItemMapper
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->taskRepository = $taskRepository;
        $this->taskToScheduleItemMapper = $taskToScheduleItemMapper;
    }

    public function fillScheduleItems(int $id): ScheduleEntity
    {
        $schedule = $this->scheduleRepository->getById($id);
        $tasks = $this->taskRepository->getByScheduleId($id);

        foreach ($tasks as $task) {
            $schedule->addItem($this->taskToScheduleItemMapper->map($task));
        }

        return $schedule;
    }
}
