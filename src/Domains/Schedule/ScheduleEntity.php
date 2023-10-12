<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Domains\Schedule;

use Tymeshift\PhpTest\Interfaces\EntityInterface;

final class ScheduleEntity implements EntityInterface
{
    private int $id;

    private string $name;

    private \DateTimeInterface $startTime;

    private \DateTimeInterface $endTime;

    /**
     * @var ScheduleItemInterface[]
     */
    private array $items;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): ScheduleEntity
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ScheduleEntity
    {
        $this->name = $name;
        return $this;
    }

    public function getStartTime(): \DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): ScheduleEntity
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): \DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): ScheduleEntity
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * @return ScheduleItemInterface[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(ScheduleItemInterface $item): self
    {
        $this->items[] = $item;

        return $this;
    }
}
