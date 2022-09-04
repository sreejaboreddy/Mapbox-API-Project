<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "../../../MapboxRequestHandler.php";
require_once __DIR__ . "../../Invoker.php";


class PathParameterExtractionTest extends TestCase {
  public $invoker;
  protected function setUp() {
    $this->invoker = new Invoker();
  }

  public function testPathParameterExtraction() {
    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/chester.json?proximity=-74.70850,40.78375&access_token=34567890';
    $obj = new MapboxRequestHandler($url);
    $yourOutput = $this->invoker->invokeMethod($obj, "pathParameterExtraction", [$url]);
    $expectedOutput = ['q' => 'chester'];
    $this->assertEquals($yourOutput, $expectedOutput);

    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/515%2015th%20St%20NW%2C%20Washington%2C%20DC%2020004.json?types=address&access_token=YOUR_MAPBOX_ACCESS_TOKEN';
    $obj = new MapboxRequestHandler($url);
    $yourOutput = $this->invoker->invokeMethod($obj, "pathParameterExtraction", [$url]);
    $expectedOutput = ['q' => '515 15th St NW, Washington, DC 20004'];
    $this->assertEquals($yourOutput, $expectedOutput);

    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/-73.989,40.733.json?access_token=654423556';
    $obj = new MapboxRequestHandler($url);
    $yourOutput = $this->invoker->invokeMethod($obj, "pathParameterExtraction", [$url]);
    $expectedOutput = ['lat' => '40.733', 'lon' => '-73.989'];
    $this->assertEquals($yourOutput, $expectedOutput);
  }

  public function testQuestionMark() {

    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/chester.jsonproximity=-74.70850,40.78375&access_token=34567890';
    $obj = new MapboxRequestHandler($url);
    $yourOutput = $this->invoker->invokeMethod($obj, "pathParameterExtraction", [$url]);
    $expectedOutput = false;
    $this->assertEquals($yourOutput, $expectedOutput);

    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/chester.proximity=-74.70850,40.78375&access_token=34567890';
    $obj = new MapboxRequestHandler($url);
    $yourOutput = $this->invoker->invokeMethod($obj, "pathParameterExtraction", [$url]);
    $expectedOutput = false;
    $this->assertEquals($yourOutput, $expectedOutput);
  }

  public function testJson() {
    $this->invoker = new Invoker();
    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/chester?proximity=-74.70850,40.78375&access_token=34567890';
    $obj = new MapboxRequestHandler($url);
    $yourOutput = $this->invoker->invokeMethod($obj, "pathParameterExtraction", [$url]);
    $expectedOutput = ['q' => 'chester'];
    $this->assertEquals($yourOutput, $expectedOutput);


    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/35.4628,4.8975.worldview=us&access_token=45678uiok567uihg';
    $obj = new MapboxRequestHandler($url);
    $yourOutput = $this->invoker->invokeMethod($obj, "pathParameterExtraction", [$url]);
    $expectedOutput = false;
    $this->assertEquals($yourOutput, $expectedOutput);
  }

  public function testString() {

    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/proximity=-74.70850,40.78375&access_token=34567890';
    $obj = new MapboxRequestHandler($url);
    $yourOutput = $this->invoker->invokeMethod($obj, "pathParameterExtraction", [$url]);
    $expectedOutput = false;
    $this->assertEquals($yourOutput, $expectedOutput);

    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/,40.733.json?access_token=654423556';
    $obj = new MapboxRequestHandler($url);
    $yourOutput = $this->invoker->invokeMethod($obj, "pathParameterExtraction", [$url]);
    $expectedOutput = false;
    $this->assertEquals($yourOutput, $expectedOutput);
  }
}
