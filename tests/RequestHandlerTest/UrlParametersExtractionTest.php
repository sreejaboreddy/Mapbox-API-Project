<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "../../../MapboxRequestHandler.php";
require_once __DIR__ . "../../Invoker.php";


class UrlParametersExtractionTest extends TestCase {
    public $invoker;
    protected function setUp() {
        $this->invoker = new Invoker();
    }


    public function testUrlParametersExtraction() {
        $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/chester.json?proximity=-74.70850,40.78375&access_token=34567890';
        $obj = new MapboxRequestHandler($url);
        $yourOutput = $this->invoker->invokeMethod($obj, "urlParametersExtraction", [$url]);
        $expectedOutput = [
            'proximity' => '-74.70850,40.78375',
            'access_token' => '34567890'
        ];
        $this->assertEquals($yourOutput, $expectedOutput);
    }

    public function testQuestionMark() {
        $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/chester.jsonproximity=-74.70850,40.78375&access_token=34567890';
        $obj = new MapboxRequestHandler($url);
        $yourOutput = $this->invoker->invokeMethod($obj, "urlParametersExtraction", [$url]);
        $expectedOutput = false;
        $this->assertEquals($yourOutput, $expectedOutput);

        $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/35.4628,4.8975.json?worldview=us&access_token=45678uiok567uihg';
        $obj = new MapboxRequestHandler($url);
        $yourOutput = $this->invoker->invokeMethod($obj, "urlParametersExtraction", [$url]);
        $expectedOutput = [
            'worldview' => 'us',
            'access_token' => '45678uiok567uihg'
        ];
        $this->assertEquals($yourOutput, $expectedOutput);
    }

    public function testJson() {
        $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/chester?proximity=-74.70850,40.78375&access_token=34567890';
        $obj = new MapboxRequestHandler($url);
        $yourOutput = $this->invoker->invokeMethod($obj, "urlParametersExtraction", [$url]);
        $expectedOutput = [
            'proximity' => '-74.70850,40.78375',
            'access_token' => '34567890'
        ];
        $this->assertEquals($yourOutput, $expectedOutput);


        $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/35.4628,4.8975?worldview=us&access_token=45678uiok567uihg';
        $obj = new MapboxRequestHandler($url);
        $yourOutput = $this->invoker->invokeMethod($obj, "urlParametersExtraction", [$url]);
        $expectedOutput = [
            'worldview' => 'us',
            'access_token' => '45678uiok567uihg'
        ];
        $this->assertEquals($yourOutput, $expectedOutput);
    }

    public function testString() {
        $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/proximity=-74.70850,40.78375';
        $obj = new MapboxRequestHandler($url);
        $yourOutput = $this->invoker->invokeMethod($obj, "urlParametersExtraction", [$url]);
        $expectedOutput = false;
        $this->assertEquals($yourOutput, $expectedOutput);

        $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/,40.733.json?access_token=654423556';
        $obj = new MapboxRequestHandler($url);
        $this->invoker = new Invoker();
        $yourOutput = $this->invoker->invokeMethod($obj, "urlParametersExtraction", [$url]);
        $expectedOutput = ['access_token' => '654423556'];
        $this->assertEquals($yourOutput, $expectedOutput);
    }
}
