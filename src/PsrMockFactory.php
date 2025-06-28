<?php

/**
 * This file is part of tomkyle/mock-psr
 *
 * Traits for mocking common PSR components in PhpUnit tests
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tomkyle\MockPsr;

use PHPUnit\Framework\TestCase;

/**
 * TestCase providing helper traits for mocking PSR interfaces.
 *
 * Extend this class to gain ready-to-use methods for creating PSR mocks.
 *
 * Usage:
 *
 * <code>
 * class SomeUnitTest extends PsrMockFactory
 * {
 *     public function testSomething(): void
 *     {
 *         $client = $this->mockClient();
 *         // ...
 *     }
 * }
 * </code>
 *
 * @internal
 *
 * @coversNothing
 */
class PsrMockFactory extends TestCase
{
    use MockPsr18ClientTrait;
    use MockPdoTrait;
    use MockPsr6CacheTrait;
    use MockPsr7MessagesTrait;
    use MockPsr11ContainerTrait;
    use MockPsr15RequestHandlerTrait;
    use MockPsr17FactoriesTrait;
}
