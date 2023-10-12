<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Schedule;

use Tymeshift\PhpTest\Domains\Task\TaskEntity;

final class TaskToScheduleItemMapper
{
    public function map(TaskEntity $task): ScheduleItemInterface
    {
        return new ScheduleItem(
            $task->getScheduleId(),
            $task->getStartTime(),
            $task->getStartTime() + $task->getDuration(),
            get_class($task)
        );
    }
}
