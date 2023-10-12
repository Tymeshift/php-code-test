<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Task;

use Tymeshift\PhpTest\Interfaces\RepositoryInterface;

/**
 * @extends RepositoryInterface<TaskEntity, TaskCollection>
 */
interface TaskRepositoryInterface extends RepositoryInterface
{
    public function getByScheduleId(int $scheduleId): TaskCollection;
}
