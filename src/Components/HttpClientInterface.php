<?php
declare(strict_types=1);

namespace Tymeshift\PhpTest\Components;

interface HttpClientInterface
{
    /**
     * Returns json decoded response body
     * @param string $method
     * @param string $uri
     * @return array<mixed>
     */
    public function request(string $method, string $uri):array;
}
