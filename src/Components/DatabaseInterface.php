<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Components;

interface DatabaseInterface
{
    /**
     * @param string $query
     * @param array<string, mixed> $params
     * @return array<mixed>
     */
    public function query(string $query, array $params):array;
}
