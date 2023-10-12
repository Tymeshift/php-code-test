<?php
declare(strict_types=1);

namespace Tests;

use Codeception\Example;
use Mockery;
use Tymeshift\PhpTest\Components\DatabaseInterface;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleFactory;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleRepository;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleRepositoryInterface;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleStorage;
use Tymeshift\PhpTest\Domains\Schedule\ScheduleStorageInterface;
use Tymeshift\PhpTest\Exceptions\StorageDataMissingException;

final class ScheduleCest
{
    private ?DatabaseInterface $databaseMock;
    private ?ScheduleStorageInterface $scheduleStorage;
    private ?ScheduleRepositoryInterface $scheduleRepository;

    public function _before()
    {
        $this->databaseMock = Mockery::mock(DatabaseInterface::class);
        $this->scheduleStorage = new ScheduleStorage($this->databaseMock);
        $this->scheduleRepository = new ScheduleRepository($this->scheduleStorage, new ScheduleFactory());
    }

    public function _after()
    {
        $this->databaseMock = null;
        $this->scheduleRepository = null;
        $this->scheduleStorage = null;
        \Mockery::close();
    }

    /**
     * @dataProvider scheduleProvider
     */
    public function testGetByIdSuccess(Example $example, \UnitTester $tester)
    {
        ['id' => $id, 'start_time' => $startTime, 'end_time' => $endTime, 'name' => $name] = $example;
        $data = ['id' => $id, 'start_time' => $startTime, 'end_time' => $endTime, 'name' => $name];

        $this->databaseMock
            ->shouldReceive('query')
            ->with('SELECT * FROM schedules WHERE id=:id', ['id' => $id])
            ->andReturn($data);

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
        $this->databaseMock
            ->shouldReceive('query')
            ->with('SELECT * FROM schedules WHERE id=:id', ['id' => 4])
            ->andReturn([]);
        $tester->expectThrowable(StorageDataMissingException::class, function () {
            $this->scheduleRepository->getById(4);
        });
    }

    /**
     * @return array<array{
     *      id: int,
     *      start_time: int,
     *      end_time: int,
     *      name: string
     *  }>
     */
    protected function scheduleProvider()
    {
        return [
            ['id' => 1, 'start_time' => 1631232000, 'end_time' => 1631232000 + 86400, 'name' => 'Test'],
        ];
    }
}
