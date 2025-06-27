<?php
namespace tomkyle\MockPsr;

use Prophecy;

trait MockPdoTrait
{

    protected function mockPdoStatement( bool $execute_result, array $fetch_result = [], array $error_info = []) : \PDOStatement
    {
        $objectProphecy = (new Prophecy\Prophet)->prophesize(\PDOStatement::class);

        $objectProphecy->setFetchMode( Prophecy\Argument::type('int'), Prophecy\Argument::any() )->willReturn( true );

        $objectProphecy->execute( Prophecy\Argument::any() )->willReturn( $execute_result );

        $objectProphecy->fetch()->willReturn( $execute_result ? $fetch_result : false );
        // $stmt->fetch(Prophecy\Argument::type('int'))->willReturn( $fetch_result );
        // $stmt->fetch(Prophecy\Argument::type('int'), Prophecy\Argument::type('int'))->willReturn( $fetch_result );
        // $stmt->fetch(Prophecy\Argument::type('int'), Prophecy\Argument::type('int'), Prophecy\Argument::type('int'))->willReturn( $fetch_result );

        $objectProphecy->fetchAll( )->willReturn( $execute_result ? $fetch_result : [] );
        // $stmt->fetchAll( Prophecy\Argument::type('int') )->willReturn( $fetch_result );
        // $stmt->fetchAll( Prophecy\Argument::type('int'), Prophecy\Argument::any() )->willReturn( $fetch_result );

        $objectProphecy->fetchObject()->willReturn( $execute_result ? (object) $fetch_result : false  );
        // $stmt->fetchObject( Prophecy\Argument::type('string') )->willReturn( $fetch_result );

        $objectProphecy->errorInfo()->willReturn($error_info );
        return $objectProphecy->reveal();
    }


    protected function mockPdo( ?\PDOStatement $pdoStatement = null) : \PDO
    {
        $pdoStatement = $pdoStatement ?: $this->mockPdoStatement(true);
        $objectProphecy = (new Prophecy\Prophet)->prophesize(\PDO::class);
        $objectProphecy->prepare( Prophecy\Argument::type('string') )->willReturn( $pdoStatement );
        return $objectProphecy->reveal();
    }

}

