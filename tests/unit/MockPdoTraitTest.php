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
    #[DataProvider('providePdoMockData')]
    public function testMockPdo($options)
    {
        $sut = new class('test') extends TestCase {
            use MockPdoTrait;
        };
        $pdo = $sut->mockPdo($options);
        $this->assertInstanceOf(\PDO::class, $pdo);
    }

    public static function providePdoMockData()
    {
        return [
            'Empty options' => [[]],
            'With options' => [['attribute' => \PDO::ATTR_ERRMODE]],
        ];
    }

    #[DataProvider('providePdoStatementMockData')]
    public function testMockPdoStatement($fetchResult, $options)
    {
        $sut = new class('test') extends TestCase {
            use MockPdoTrait;
        };
        $stmt = $sut->mockPdoStatement($fetchResult, $options);
        $this->assertInstanceOf(\PDOStatement::class, $stmt);

        if (null !== $fetchResult) {
            $this->assertEquals($fetchResult, $stmt->fetch());
        }
    }

    public static function providePdoStatementMockData()
    {
        return [
            'Empty result' => [null, []],
            'Array result' => [['id' => 1, 'name' => 'test'], []],
            'String result' => ['test', []],
            'With options' => [['data'], ['rowCount' => 5]],
        ];
    }
}
