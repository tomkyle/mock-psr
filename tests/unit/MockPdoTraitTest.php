<?php
namespace tests;

use tomkyle\MockPsr\MockPdoTrait;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class MockPdoTraitTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait,
        // SUT
        MockPdoTrait;


    /**
     * @dataProvider provideStatements
     */
    public function testMockPdo( $stmt = null) : void
    {
        $pdo = $this->mockPdo($stmt);
        $this->assertInstanceOf( \PDO::class, $pdo);

        $stmt = $pdo->prepare("SELECT * FROM table WHERE 1");
        $this->assertInstanceOf(\PDOStatement::class, $stmt);
    }


    public function provideStatements() : array
    {
        return array(
            'No parameters' => [  ],
        );
    }


    /**
     * @dataProvider provideStatementParameters
     */
    public function testMockPdoStatement( bool $execute_result, array $fetch_result = array(), array $error_info = array() ) : void
    {
        $stmt = $this->mockPdoStatement($execute_result, $fetch_result, $error_info);

        $this->assertInstanceOf(\PDOStatement::class, $stmt);

        if ($execute_result) {
            $this->assertEquals($stmt->fetch(), $fetch_result);
            $this->assertEquals($stmt->fetchAll(), $fetch_result);
            $this->assertEquals($stmt->fetchObject(), (object) $fetch_result);
        }
        else {
            $this->assertFalse($stmt->fetch());
            $this->assertFalse($stmt->fetchObject());

            $fetch_all = $stmt->fetchAll();
            $this->assertIsArray($fetch_all);
            $this->assertTrue(empty($fetch_all));

        }

        $this->assertEquals($stmt->errorInfo(), $error_info);
    }

    public function provideStatementParameters() : array
    {
        return array(
            "execute yields 'true'" => [ true ],
            "execute yields 'false'" => [ false ],
            "Certain result" => [ true, array("foo" => "bar") ],
            "w/ error" => [ true, array("foo" => "bar"), ["42S02", "-204", "BUUUH!"] ],
        );
    }

}
