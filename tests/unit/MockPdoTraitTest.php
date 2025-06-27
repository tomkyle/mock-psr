<?php

/**
 * This file is part of tomkyle/mock-psr
 *
 * Traits for mocking common PSR components in PhpUnit tests
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use tomkyle\MockPsr\MockPdoTrait;

/**
 * @internal
 *
 * @coversNothing
 */
class MockPdoTraitTest extends TestCase
{
    // SUT
    use MockPdoTrait;

    #[DataProvider('provideStatements')]
    public function testMockPdo($stmt = null): void
    {
        $pdo = $this->mockPdo($stmt);
        $this->assertInstanceOf(\PDO::class, $pdo);

        $stmt = $pdo->prepare('SELECT * FROM table WHERE 1');
        $this->assertInstanceOf(\PDOStatement::class, $stmt);
    }

    public static function provideStatements(): array
    {
        return [
            'No parameters' => [],
        ];
    }

    #[DataProvider('provideStatementParameters')]
    public function testMockPdoStatement(bool $execute_result, array $fetch_result = [], array $error_info = []): void
    {
        $stmt = $this->mockPdoStatement($execute_result, $fetch_result, $error_info);

        $this->assertInstanceOf(\PDOStatement::class, $stmt);

        if ($execute_result) {
            $this->assertEquals($stmt->fetch(), $fetch_result);
            $this->assertEquals($stmt->fetchAll(), $fetch_result);
            $this->assertEquals($stmt->fetchObject(), (object) $fetch_result);
        } else {
            $this->assertFalse($stmt->fetch());
            $this->assertFalse($stmt->fetchObject());

            $fetch_all = $stmt->fetchAll();
            $this->assertIsArray($fetch_all);
            $this->assertTrue(empty($fetch_all));
        }

        $this->assertEquals($stmt->errorInfo(), $error_info);
    }

    public static function provideStatementParameters(): array
    {
        return [
            "execute yields 'true'" => [true],
            "execute yields 'false'" => [false],
            'Certain result' => [true, ['foo' => 'bar']],
            'w/ error' => [true, ['foo' => 'bar'], ['42S02', '-204', 'BUUUH!']],
        ];
    }
}
