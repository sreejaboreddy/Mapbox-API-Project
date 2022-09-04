<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "../../../MapboxResponseHandler.php";
require_once __DIR__ . "../../Invoker.php";

class RequestResponseTest extends TestCase {
    public $invoker;
  
    protected function setUp() {
      $this->invoker = new Invoker();
    }

    /**
     * @test
     */
    public function testQueryForForwardGeocoding() {
      $obj = new MapboxResponseHandler();
      $yourOutput = $this->invoker->invokeMethod($obj, "queryExtraction", ['https://us1.locationiq.com/v1/reverse.php?0%5Blat%5D=-73.989&1%5Blon%5D=40.733&key=pk.872a9961e54cbc6807cbbe069&format=json']);
      $expectedOutput =[];
      $this->assertEquals($yourOutput, $expectedOutput);

      $yourOutput = $this->invoker->invokeMethod($obj, "queryExtraction", ['https://us1.locationiq.com/v1/reverse.php?lat=4.8975&lon=35.4628&format=json&key=45678uiok567uihg']);
      $expectedOutput =[];
      $this->assertEquals($yourOutput, $expectedOutput);
      
    
    }
  
}