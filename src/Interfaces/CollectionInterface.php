<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Interfaces;

/**
 * @template TValue of EntityInterface
 * @template TFactory of FactoryInterface
 */
interface CollectionInterface
{
    /**
     * Adds item to collection
     * @param TValue $entity
     * @return $this
     */
    public function add(EntityInterface $entity):self;

    /**
     * Creates Collection from array
     * @param array<mixed> $data
     * @param TFactory $factory
     * @return $this
     */
    public function createFromArray(array $data, FactoryInterface $factory):self;

    /**
     * Creates array from collection
     * @return array<mixed>
     */
    public function toArray():array;
}
