<?php
declare(strict_types=1);

namespace Tests;

use Codeception\Example;
use Mockery;
use Tymeshift\PhpTest\Components\DatabaseInterface;
use Tymeshift\PhpTest\Components\HttpClientInterface;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleEntity;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleFactory;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleItem;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleRepository;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleService;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleStorage;
use Tymeshift\PhpTest\Domains\Schedule\TaskToScheduleItemMapper;
use Tymeshift\PhpTest\Domains\Task\TaskEntity;
use Tymeshift\PhpTest\Domains\Task\TaskFactory;
use Tymeshift\PhpTest\Domains\Task\TaskRepository;
use Tymeshift\PhpTest\Domains\Task\TaskStorage;
use UnitTester;

final class ScheduleServiceCest
{
    private ?DatabaseInterface $databaseMock;
    private ?HttpClientInterface $httpClientMock;
    private ?ScheduleService $scheduleService;

    public function _before(): void
    {
        $this->httpClientMock = Mockery::mock(HttpClientInterface::class);
        $this->databaseMock = Mockery::mock(DatabaseInterface::class);
        $this->scheduleService = new ScheduleService(
            new ScheduleRepository(
                new ScheduleStorage(
                    $this->databaseMock
                ),
                new ScheduleFactory()
            ),
            new TaskRepository(
                new TaskStorage(
                    $this->httpClientMock
                ),
                new TaskFactory()
            ),
            new TaskToScheduleItemMapper()
        );
    }

    public function _after(): void
    {
        $this->httpClientMock = null;
        $this->databaseMock = null;
        Mockery::close();
    }

    /**
     * @dataProvider tasksDataProvider
     */
    public function testFillScheduleItemsSuccess(Example $example, UnitTester $tester): void
    {
        $this->httpClientMock
            ->shouldReceive('request')
            ->with('GET', '/api/tasks/schedule/1')
            ->andReturn([...$example]);

        $this->databaseMock
            ->shouldReceive('query')
            ->with('SELECT * FROM schedules WHERE id=:id', ["id" => 1])
            ->andReturn(['id' => 1, 'start_time' => 111111, 'end_time' => 22222 + 33333, 'name' => 'XYZ']);

        $schedule = $this->scheduleService->fillScheduleItems(1);
        $items = $schedule->getItems();
        $tester->assertInstanceOf(ScheduleEntity::class, $schedule);
        foreach ($items as $item) {
            $tester->assertInstanceOf(ScheduleItem::class, $item);
            $tester->assertEquals($item->getType(), TaskEntity::class);
        }
        $tester->assertCount(3, $items);
    }

    /**
     * @return array<array<array{id: int, schedule_id: int, start_time: int, duration: int}>>
     */
    protected function tasksDataProvider(): array
    {
        return [
            [
                ["id" => 123, "schedule_id" => 1, "start_time" => 0, "duration" => 3600],
                ["id" => 431, "schedule_id" => 1, "start_time" => 3600, "duration" => 650],
                ["id" => 332, "schedule_id" => 1, "start_time" => 5600, "duration" => 3600],
            ],
        ];
    }
}
