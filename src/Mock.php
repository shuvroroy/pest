<?php

declare(strict_types=1);

namespace Pest;

use Mockery;
use Pest\Exceptions\MissingDependency;

/**
 * @mixin \Mockery\MockInterface
 */
final class Mock
{
    /**
     * The object being mocked.
     *
     * @readonly
     *
     * @var \Mockery\MockInterface|\Mockery\LegacyMockInterface
     */
    private $mock;

    /**
     * Creates a new mock instance.
     *
     * @param string|object $object
     */
    public function __construct($object)
    {
        if (!class_exists(Mockery::class)) {
            throw new MissingDependency('Mocking', 'mockery/mockery');
        }

        $this->mock = Mockery::mock($object);
    }

    /**
     * Define mock expectations.
     *
     * @param mixed ...$methods
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface
     */
    public function expect(...$methods)
    {
        foreach ($methods as $method => $result) {
            /* @phpstan-ignore-next-line */
            $this->mock
                ->shouldReceive((string) $method)
                ->andReturn($result);
        }

        return $this->mock;
    }

    /**
     * Proxies calls to the original mock object.
     *
     * @param array<int, mixed> $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        /* @phpstan-ignore-next-line */
        return $this->mock->{$method}($arguments);
    }
}
