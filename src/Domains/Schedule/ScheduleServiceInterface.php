<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Schedule;

interface ScheduleServiceInterface
{
    public function fillScheduleItems(int $id): ScheduleEntity;
}
