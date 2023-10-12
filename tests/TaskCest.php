<?php
declare(strict_types=1);

namespace Tests;

use Codeception\Example;
use Tymeshift\PhpTest\Components\HttpClientInterface;
use Tymeshift\PhpTest\Domains\Task\TaskCollection;
use Tymeshift\PhpTest\Domains\Task\TaskEntity;
use Tymeshift\PhpTest\Domains\Task\TaskFactory;
use Tymeshift\PhpTest\Domains\Task\TaskRepository;
use Tymeshift\PhpTest\Domains\Task\TaskStorage;
use UnitTester;

final class TaskCest
{
    private ?TaskRepository $taskRepository;

    private ?HttpClientInterface $httpClientMock;

    public function _before()
    {
        $this->httpClientMock = \Mockery::mock(HttpClientInterface::class);

        $storage = new TaskStorage($this->httpClientMock);
        $this->taskRepository = new TaskRepository($storage, new TaskFactory());
    }

    public function _after()
    {
        $this->taskRepository = null;
        $this->httpClientMock = null;
        \Mockery::close();
    }

    /**
     * @dataProvider tasksDataProvider
     */
    public function testGetTasksByScheduleSuccess(Example $example, UnitTester $tester): void
    {
        $this->httpClientMock
            ->shouldReceive('request')
            ->with('GET', '/api/tasks/schedule/1')
            ->andReturn([...$example]);

        $tasks = $this->taskRepository->getByScheduleId(1);
        $tester->assertInstanceOf(TaskCollection::class, $tasks);
    }

    /**
     * @dataProvider tasksDataProvider
     */
    public function testGetTasks(Example $example, \UnitTester $tester)
    {
        $ids = [123, 431, 332];
        $this->httpClientMock->shouldReceive('request')
            ->with('GET', '/api/tasks?'.http_build_query(['ids' => $ids]))
            ->andReturn([...$example]);

        $tasks = $this->taskRepository->getByIds($ids);
        $tester->assertInstanceOf(TaskCollection::class, $tasks);
    }

    /**
     * @dataProvider tasksDataProvider
     */
    public function testGetTask(Example $example, UnitTester $tester): void
    {
        $data = [...$example][0];

        $this->httpClientMock
            ->shouldReceive('request')
            ->with('GET', '/api/tasks/1')
            ->andReturn($data);

        $task = $this->taskRepository->getById(1);
        $tester->assertInstanceOf(TaskEntity::class, $task);
    }

    public function testGetTaskFailed(UnitTester $tester): void
    {
        $this->httpClientMock
            ->shouldReceive('request')
            ->with('GET', '/api/tasks/4')
            ->andReturn([]);

        $tester->expectThrowable(\Exception::class, function () {
            $this->taskRepository->getById(4);
        });
    }

    /**
     * @return array<array<array{
     *     id: int,
     *     schedule_id: int,
     *     start_time: int,
     *     duration: int
     * }>>
     */
    public function tasksDataProvider(): array
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
