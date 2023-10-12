<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Interfaces;

use Tymeshift\PhpTest\Exceptions\InvalidCollectionDataProvidedException;

/**
 * @template TEntity of EntityInterface
 * @template TCollection of CollectionInterface
 */
interface RepositoryInterface
{
    /**
     * @return TEntity
     */
    public function getById(int $id): EntityInterface;

    /**
     * @param array<int> $ids
     *
     * @return TCollection
     *
     * @throws InvalidCollectionDataProvidedException
     */
    public function getByIds(array $ids): CollectionInterface;
}
