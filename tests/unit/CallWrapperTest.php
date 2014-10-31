<?php 
/**
 * Test ArtisanWrapperTest
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
use Mockery as m;
class CallWrapperTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     **/
    public function itCallsGivenMethodOnWrappedClass()
    {
      $argument = ["param1", "param2"];
      $mock_called = m::mock('StdClass')
              ->shouldReceive('call')
              ->once()
              ->with($argument)
              ->getMock();
      $wrapper = new CallWrapper($mock_called);
      $wrapper->call($argument);
    }
}
 