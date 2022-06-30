<?php
namespace tomkyle\MockPsr;

use Prophecy\Argument;

trait MockPdoTrait
{
    
    protected function mockPdoStatement( bool $execute_result, array $fetch_result = array(), array $error_info = array()) : \PDOStatement
    {
        $prophet = new \Prophecy\Prophet;

        $stmt = $prophet->prophesize(\PDOStatement::class);

        $stmt->setFetchMode( Argument::type('int'), Argument::any() )->willReturn( true );

        $stmt->execute( Argument::any() )->willReturn( $execute_result );

        $stmt->fetch()->willReturn( $execute_result ? $fetch_result : false );
        // $stmt->fetch(Argument::type('int'))->willReturn( $fetch_result );
        // $stmt->fetch(Argument::type('int'), Argument::type('int'))->willReturn( $fetch_result );
        // $stmt->fetch(Argument::type('int'), Argument::type('int'), Argument::type('int'))->willReturn( $fetch_result );
        
        $stmt->fetchAll( )->willReturn( $execute_result ? $fetch_result : array() );
        // $stmt->fetchAll( Argument::type('int') )->willReturn( $fetch_result );
        // $stmt->fetchAll( Argument::type('int'), Argument::any() )->willReturn( $fetch_result );
        
        $stmt->fetchObject()->willReturn( $execute_result ? (object) $fetch_result : false  );
        // $stmt->fetchObject( Argument::type('string') )->willReturn( $fetch_result );
        
        $stmt->errorInfo()->willReturn($error_info );
        return $stmt->reveal();
    }


    protected function mockPdo( \PDOStatement $stmt_mock = null) : \PDO
    {
        $prophet = new \Prophecy\Prophet;
        $stmt_mock = $stmt_mock ?: $this->mockPdoStatement(true);
        $pdo = $prophet->prophesize(\PDO::class);
        $pdo->prepare( Argument::type('string') )->willReturn( $stmt_mock );
        return $pdo->reveal();
    }

}

