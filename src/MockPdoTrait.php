<?php
namespace tomkyle\MockPsr;

use Prophecy;

trait MockPdoTrait
{
    
    protected function mockPdoStatement( bool $execute_result, array $fetch_result = array(), array $error_info = array()) : \PDOStatement
    {
        $stmt = (new Prophecy\Prophet)->prophesize(\PDOStatement::class);

        $stmt->setFetchMode( Prophecy\Argument::type('int'), Prophecy\Argument::any() )->willReturn( true );

        $stmt->execute( Prophecy\Argument::any() )->willReturn( $execute_result );

        $stmt->fetch()->willReturn( $execute_result ? $fetch_result : false );
        // $stmt->fetch(Prophecy\Argument::type('int'))->willReturn( $fetch_result );
        // $stmt->fetch(Prophecy\Argument::type('int'), Prophecy\Argument::type('int'))->willReturn( $fetch_result );
        // $stmt->fetch(Prophecy\Argument::type('int'), Prophecy\Argument::type('int'), Prophecy\Argument::type('int'))->willReturn( $fetch_result );
        
        $stmt->fetchAll( )->willReturn( $execute_result ? $fetch_result : array() );
        // $stmt->fetchAll( Prophecy\Argument::type('int') )->willReturn( $fetch_result );
        // $stmt->fetchAll( Prophecy\Argument::type('int'), Prophecy\Argument::any() )->willReturn( $fetch_result );
        
        $stmt->fetchObject()->willReturn( $execute_result ? (object) $fetch_result : false  );
        // $stmt->fetchObject( Prophecy\Argument::type('string') )->willReturn( $fetch_result );
        
        $stmt->errorInfo()->willReturn($error_info );
        return $stmt->reveal();
    }


    protected function mockPdo( \PDOStatement $stmt_mock = null) : \PDO
    {
        $stmt_mock = $stmt_mock ?: $this->mockPdoStatement(true);
        $pdo = (new Prophecy\Prophet)->prophesize(\PDO::class);
        $pdo->prepare( Prophecy\Argument::type('string') )->willReturn( $stmt_mock );
        return $pdo->reveal();
    }

}

