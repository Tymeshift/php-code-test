<?php
declare(strict_types=1);

namespace Tests;

use Codeception\Example;
use Codeception\Test\Unit;
use Mockery\Mock;
use Tymeshift\PhpTest\Components\HttpClientInterface;
use Tymeshift\PhpTest\Domains\Task\TaskCollection;
use Tymeshift\PhpTest\Domains\Task\TaskFactory;
use Tymeshift\PhpTest\Domains\Task\TaskRepository;
use Tymeshift\PhpTest\Domains\Task\TaskStorage;
use Tymeshift\PhpTest\Exceptions\InvalidCollectionDataProvidedException;

class TaskCest
{

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @var Mock|HttpClientInterface
     */
    private $httpClientMock;


    public function _before()
    {
        $this->httpClientMock = \Mockery::mock(HttpClientInterface::class);
        $storage = new TaskStorage($this->httpClientMock);
        $this->taskRepository = new TaskRepository($storage, new TaskFactory());
    }

    public function _after()
    {
        $this->taskRepository = null;
        \Mockery::close();
    }

    /**
     * @dataProvider tasksDataProvider
     * @throws InvalidCollectionDataProvidedException
     */
    public function testGetTasks(Example $example, \UnitTester $tester)
    {
        $data = (array) $example->getIterator();
        $this->httpClientMock->shouldReceive('request')
            ->once()
            ->with('GET', 'https://tymeshift.com/get-by-schedule-id/1')
            ->andReturn($data);

        $tasks = $this->taskRepository->getByScheduleId(1);
        $tester->assertInstanceOf(TaskCollection::class, $tasks);
    }

    public function testGetTasksFailed(\UnitTester $tester)
    {
        $tester->expectThrowable(\Exception::class, function (){
            $this->taskRepository->getByScheduleId(4);
        });
    }

    public function tasksDataProvider(): array
    {
        return [
            [
                ["id" => 123, "schedule_id" => 1, "start_time" => 0, "duration" => 3600],
                ["id" => 431, "schedule_id" => 1, "start_time" => 3600, "duration" => 650],
                ["id" => 332, "schedule_id" => 1, "start_time" => 5600, "duration" => 3600],
            ]
        ];
    }
}