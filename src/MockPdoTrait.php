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

use PHPUnit\Framework\MockObject\MockObject;

trait MockPdoTrait
{
    /**
     * Create a mock PDO instance.
     *
     * Returns a mock PDO object configured to return mock statements and controlled attributes.
     *
     * Usage:
     *
     * <code>
     * $pdo = $this->mockPdo(['attribute' => PDO::ATTR_ERRMODE]);
     * $stmt = $pdo->prepare('SELECT * FROM table');
     * </code>
     *
     * @param array $options options for configuring PDO: 'attribute' => mixed
     *
     * @return \PDO a mock PDO instance
     */
    public function mockPdo(array $options = []): \PDO
    {
        /** @var MockObject&\PDO $pdo */
        $pdo = $this->createMock(\PDO::class);

        $pdo->method('getAttribute')->willReturnCallback(fn () => $options['attribute'] ?? null);

        $pdo->method('setAttribute')->willReturn(true);
        $pdo->method('prepare')->willReturnCallback(fn () => $this->mockPdoStatement());

        $pdo->method('query')->willReturnCallback(fn () => $this->mockPdoStatement());

        $pdo->method('exec')->willReturn(1);
        $pdo->method('lastInsertId')->willReturn('1');
        $pdo->method('beginTransaction')->willReturn(true);
        $pdo->method('commit')->willReturn(true);
        $pdo->method('rollBack')->willReturn(true);
        $pdo->method('inTransaction')->willReturn(false);

        return $pdo;
    }

    /**
     * Create a mock PDOStatement.
     *
     * Returns a mock PDOStatement configured to return provided fetch results and options.
     *
     * Usage:
     *
     * <code>
     * $stmt = $this->mockPdoStatement(['id' => 1], ['rowCount' => 1]);
     * $stmt->fetch(); // ['id' => 1]
     * $stmt->rowCount(); // 1
     * </code>
     *
     * @param mixed $fetchResult value to be returned by fetch() and fetchColumn()
     * @param array $options     options for configuring statement: 'rowCount' and 'columnCount'
     *
     * @return \PDOStatement a mock PDOStatement instance
     */
    public function mockPdoStatement($fetchResult = null, array $options = []): \PDOStatement
    {
        /** @var MockObject&\PDOStatement $stmt */
        $stmt = $this->createMock(\PDOStatement::class);

        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetch')->willReturn($fetchResult);
        $stmt->method('fetchAll')->willReturn(is_array($fetchResult) ? [$fetchResult] : []);
        $stmt->method('fetchColumn')->willReturn(is_array($fetchResult) ? reset($fetchResult) : $fetchResult);
        $stmt->method('rowCount')->willReturn($options['rowCount'] ?? 1);
        $stmt->method('columnCount')->willReturn($options['columnCount'] ?? 1);

        $stmt->method('bindParam')->willReturn(true);
        $stmt->method('bindValue')->willReturn(true);
        $stmt->method('bindColumn')->willReturn(true);

        $stmt->method('closeCursor')->willReturn(true);
        $stmt->method('errorCode')->willReturn('00000');
        $stmt->method('errorInfo')->willReturn(['00000', null, null]);

        return $stmt;
    }
}
