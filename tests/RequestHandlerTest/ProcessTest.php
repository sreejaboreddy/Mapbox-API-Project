<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "../../../MapboxRequestHandler.php";
require_once __DIR__ . "../../Invoker.php";


class ProcessTest extends TestCase {
  public $invoker;

  protected function setUp() {
    $this->invoker = new Invoker();
  }

  public function testProcessForForwardGeocoding() {
    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/chester.json?proximity=-74.70850,40.78375&access_token=34234kj';
    $objMock = \Mockery::mock('MapboxRequestHandler[buildUrl,mapParametersToLIQFormat, urlParametersExtraction,pathParameterExtraction]', [$url]);
    $objMock->shouldReceive('pathParameterExtraction()')->with($url)->andReturn(['q' => 'chester']);
    $objMock->shouldReceive('urlParametersExtraction()')->with($url)->andReturn(['proximity' => '-74.70850,40.78375', 'access_token' => '34234kj']);
    $objMock->shouldReceive('mapParametersToLIQFormat()')->with(['proximity' => '-74.70850,40.78375', 'access_token' => '34234kj'])->andReturn(['format' => 'json', 'key' => '34234kj']);
    $objMock->shouldReceive('buildUrl()')->with(['q' => 'chester'], ['format' => 'json', 'key' => '34234kj'])->andReturn('https://us1.locationiq.com/v1/search.php?q=chester&format=json&key=34234kj');
    $yourOutput = $this->invoker->invokeMethod($objMock, "process", [$url]);
    $expectedOutput = "https://us1.locationiq.com/v1/search.php?q=chester&format=json&key=34234kj";
    $this->assertEquals($yourOutput, $expectedOutput);
  }

  public function testProcessForReverseGeocoding() {
    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/-73.989,40.733.json?access_token=654423556';
    $objMock = \Mockery::mock('MapboxRequestHandler[buildUrl,mapParametersToLIQFormat, urlParametersExtraction,pathParameterExtraction]', [$url]);
    $objMock->shouldReceive('pathParameterExtraction()')->with($url)->andReturn(['lat' => '40.733', 'lon' => '-73.989']);
    $objMock->shouldReceive('urlParametersExtraction()')->with($url)->andReturn(['access_token' => '654423556']);
    $objMock->shouldReceive('mapParametersToLIQFormat()')->with(['access_token' => '654423556'])->andReturn(['format' => 'json', 'key' => '654423556']);
    $objMock->shouldReceive('buildUrl()')->with(['lat' => '40.733', 'lon' => '-73.989'], ['key' => '654423556'])->andReturn('https://us1.locationiq.com/v1/reverse.php?lat=40.733&lon=-73.989&format=json&key=654423556');
    $yourOutput = $this->invoker->invokeMethod($objMock, "process", [$url]);
    $expectedOutput = "https://us1.locationiq.com/v1/reverse.php?lat=40.733&lon=-73.989&format=json&key=654423556";
    $this->assertEquals($yourOutput, $expectedOutput);
  }
}
