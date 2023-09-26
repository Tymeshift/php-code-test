<?php
declare(strict_types=1);
namespace Tests;

use Codeception\Example;
use Mockery\MockInterface;
use Tymeshift\PhpTest\Components\HttpClientInterface;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleService;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleItemInterface;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleRepository;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleFactory;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleStorage;
use Tymeshift\PhpTest\Exceptions\InvalidCollectionDataProvidedException;
use Tymeshift\PhpTest\Exceptions\StorageDataMissingException;
use Tymeshift\PhpTest\Domains\Task\TaskRepository;
use Tymeshift\PhpTest\Domains\Task\TaskStorage;
use Tymeshift\PhpTest\Domains\Task\TaskFactory;

class ScheduleCest
{
    /**
     * @var MockInterface|ScheduleStorage
     */
    private $scheduleStorageMock;

    /**
     * @var ScheduleRepository
     */
    private $scheduleRepository;
    
    public function _before()
    {
        $this->scheduleStorageMock = \Mockery::mock(ScheduleStorage::class);
        $this->scheduleRepository = new ScheduleRepository($this->scheduleStorageMock, new ScheduleFactory());
    }

    public function _after()
    {
        $this->scheduleRepository = null;
        $this->scheduleStorageMock = null;
        \Mockery::close();
    }

    /**
     * @dataProvider scheduleProvider
     */
    public function testGetByIdSuccess(Example $example, \UnitTester $tester)
    {
        ['id' => $id, 'start_time' => $startTime, 'end_time' => $endTime, 'name' => $name] = $example;
        $data = ['id' => $id, 'start_time' => $startTime, 'end_time' => $endTime, 'name' => $name];

        $this->scheduleStorageMock
            ->shouldReceive('getById')
            ->with($id)
            ->andReturn(['id' => $id, 'start_time' => $startTime, 'end_time' => $endTime, 'name' => $name]);

        $entity = $this->scheduleRepository->getById($id);
        $tester->assertEquals($id, $entity->getId());
        $tester->assertEquals($startTime, $entity->getStartTime()->getTimestamp());
        $tester->assertEquals($endTime, $entity->getEndTime()->getTimestamp());
    }

    /**
     * @param \UnitTester $tester
     */
    public function testGetByIdFail(\UnitTester $tester)
    {
        $this->scheduleStorageMock
            ->shouldReceive('getById')
            ->with(4)
            ->andReturn([]);
        $tester->expectThrowable(StorageDataMissingException::class, function () {
            $this->scheduleRepository->getById(4);
        });
    }

    /**
     * @dataProvider tasksDataProvider
     * @throws InvalidCollectionDataProvidedException
     * @throws StorageDataMissingException
     */
    public function testService(Example $example, \UnitTester $tester)
    {
        $httpClientMock = \Mockery::mock(HttpClientInterface::class);

        $data = (array) $example->getIterator();
        $httpClientMock->shouldReceive('request')
            ->once()
            ->with('GET', 'https://tymeshift.com/get-by-schedule-id/1')
            ->andReturn($data);

        $taskStorage = new TaskStorage($httpClientMock);
        $taskRepository = new TaskRepository($taskStorage, new TaskFactory());

        $scheduleService = new ScheduleService(
            $this->scheduleRepository,
            $taskRepository
        );

        $schedule = $scheduleService->setTasks(1);
        $tester->assertContainsOnlyInstancesOf(
            ScheduleItemInterface::class,
            $schedule->getScheduleItems()
        );
    }

    /**
     * @return array[]
     */
    protected function scheduleProvider()
    {
        return [
            ['id' => 1, 'start_time' => 1631232000, 'end_time' => 1631232000 + 86400, 'name' => 'Test'],
        ];
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