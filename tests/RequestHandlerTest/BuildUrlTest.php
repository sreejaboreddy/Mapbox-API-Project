<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "../../../MapboxRequestHandler.php";
require_once __DIR__ . "../../Invoker.php";


class BuildUrlTest extends TestCase {
  public $invoker;

  protected function setUp() {
    $this->invoker = new Invoker();
  }

  public function testBuildUrl() {
    $parameters = [
      'format' => 'json',
      'key' => 'pk.872a9961e54cbc6807cbbe06acaa6359'
    ];
    $search_text = ['q' => '515 15th St NW, Washington, DC 20004'];
    $obj = new MapboxRequestHandler('');
    $yourOutput = $this->invoker->invokeMethod($obj, "buildUrl", [$search_text, $parameters]);
    $expectedOutput = "https://us1.locationiq.com/v1/search.php?q=515+15th+St+NW%2C+Washington%2C+DC+20004&format=json&key=pk.872a9961e54cbc6807cbbe06acaa6359";
    $this->assertEquals($yourOutput, $expectedOutput);


    $parameters = [
      'format' => 'json',
      'key' => 'pk.872a9961e54cbc6807cbbe06acaa6359',
      'limit' => '2'
    ];
    $search_text = ['q' => 'los vegas'];
    $yourOutput = $this->invoker->invokeMethod($obj, "buildUrl", [$search_text, $parameters]);
    $expectedOutput = "https://us1.locationiq.com/v1/search.php?q=los+vegas&format=json&key=pk.872a9961e54cbc6807cbbe06acaa6359&limit=2";
    $this->assertEquals($yourOutput, $expectedOutput);


   //Doubt
    // $parameters = [
    //   'format' => 'json',
    //   'key' => 'pk.872a9961e54cbc6807cbbe06acaa6359',
    //   'limit' => '2'
    // ];
    // $search_text=['lat'=>'40.733' , 'lon'=>'-73.989'];
    // $yourOutput = $this->invoker->invokeMethod($obj, "buildUrl", [$search_text,$parameters]);
    // $expectedOutput = "https://us1.locationiq.com/v1/reverse.php?lat=40.733&lon=-73.989&format=json&key=pk.872a9961e54cbc6807cbbe06acaa6359&limit=2";
    // $this->assertEquals($yourOutput, $expectedOutput);

  }
}
