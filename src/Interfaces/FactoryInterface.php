<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Interfaces;

use Tymeshift\PhpTest\Exceptions\InvalidCollectionDataProvidedException;

/**
 * @template TData of array
 * @template TCollection of CollectionInterface
 */
interface FactoryInterface
{
    /**
     * @param TData $data
     */
    public function createEntity(array $data): EntityInterface;


    /**
     * @param array<TData> $data
     * @return TCollection
     * @throws InvalidCollectionDataProvidedException
     */
    public function createCollection(array $data): CollectionInterface;
}
