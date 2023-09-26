<?php

namespace Tymeshift\PhpTest\Domains\Schedule;

use Tymeshift\PhpTest\Domains\Schedule\ScheduleItemInterface;
use Tymeshift\PhpTest\Interfaces\EntityInterface;

class ScheduleItemEntity implements EntityInterface, ScheduleItemInterface
{
    private int $scheduleId;
    
    private int $startTime;
    
    private int $endTime;

    private string $type;
    
    public function getScheduleId(): int
    {
        return $this->scheduleId;
    }

    public function setScheduleId(int $scheduleId): ScheduleItemEntity
    {
        $this->scheduleId = $scheduleId;
        return $this;
    }
    
    public function getStartTime(): int
    {
        return $this->startTime;
    }

    public function setStartTime(int $startTime): ScheduleItemEntity
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function getEndTime(): int
    {
        return $this->endTime;
    }

    public function setEndTime(int $endTime): ScheduleItemEntity
    {
        $this->endTime = $endTime;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): ScheduleItemEntity
    {
        $this->type = $type;
        return $this;
    }
}