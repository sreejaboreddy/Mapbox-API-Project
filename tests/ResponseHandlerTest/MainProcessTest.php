<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "../../../MapboxResponseHandler.php";
require_once __DIR__ . "../../Invoker.php";

class MainProcessTest extends TestCase {
    public $invoker;
  
    protected function setUp() {
      $this->invoker = new Invoker();
    }
    public function testForError() {
      $obj = new MapboxResponseHandler();
      $url ='https://us1.locationiq.com/v1/search.php?q=georgia&key=698674&format=json';
      $query =['q'=>'georgia'];
      $error = false;
      $errorCode=null;
      $yourOutput = $this->invoker->invokeMethod($obj, "mainProcess", [$url , $query ,$error, $errorCode]);
      $expectedOutput ='{"message":"Not Authorized - Invalid Token"}';
      $this->assertEquals($yourOutput, $expectedOutput);


      $url ='https://us1.locationiq.com/v1/search.php?q=georgia&key=698674&format=json';
      $query =['q'=>'georgia'];
      $error = true;
      $errorCode=null;
      $yourOutput = $this->invoker->invokeMethod($obj, "mainProcess", [$url , $query ,$error, $errorCode]);
      $expectedOutput ='';
      $this->assertEquals($yourOutput, $expectedOutput);


      $url ='https://us1.locationiq.com/v1/search.php?&key=698674&format=json';
      $query =[];
      $error = true;
      $errorCode=400;
      $yourOutput = $this->invoker->invokeMethod($obj, "mainProcess", [$url , $query ,$error, $errorCode]);
      $expectedOutput ='{"message":"Not Found"}';
      $this->assertEquals($yourOutput, $expectedOutput);

      $url ='https://us1.locationiq.com/v1/search.php?q=Hyderabad';
      $query =['q'=>'Hyderabad'];
      $error = true;
      $errorCode=400;
      $yourOutput = $this->invoker->invokeMethod($obj, "mainProcess", [$url , $query ,$error, $errorCode]);
      $expectedOutput ='{"message":"Not Found"}';
      $this->assertEquals($yourOutput, $expectedOutput);

    }

     /**
     * @test
     */
    public function testQueryForReverseGeocoding() {
      $objMock = \Mockery::mock('MapboxResponseHandler[mapboxGeoJsonCreation,requestResponse]', []);
      $decode = [
        "place_id" => "352631455",
        "licence" => "https://locationiq.com/attribution",
        "osm_type" => "relation",
        "osm_id" => "1879842",
        "lat" => "51.50344025",
        "lon" => "-0.12770820958562096",
        "display_name" => "10 Downing Street, 10, Downing Street, Westminster, Lambeth, London, Greater London, England, SW1A 2AA, United Kingdom",
        "address" => [
          "government" => "10 Downing Street",
          "house_number" => "10",
          "road" => "Downing Street",
          "quarter" => "Westminster",
          "suburb" => "Lambeth",
          "city" => "London",
          "county" => "Greater London",
          "state" => "England",
          "postcode" => "SW1A 2AA",
          "country" => "United Kingdom",
          "country_code" => "gb"
        ],
        "boundingbox" => [
          "51.5032573",
          "51.5036483",
          "-0.1278356",
          "-0.1273038"
        ]
      ];
      $error = false;
      $errorCode=null;
      $query = ['lat'=>'51.50344025','lon'=>'-0.12770820958562096'];
      $objMock->shouldReceive('mapboxGeoJsonCreation()')->with([$decode , $query])->andReturn('{"type":"FeatureCollection","query":["-0.12770820958562096","51.50344025"],"features":{"id":"352631455","type":"Feature","place-type":[null],"properties":[],"bbox":["51.5032573","51.5036483","-0.1278356","-0.1273038"],"text":"","place_name":"10 Downing Street, 10, Downing Street, Westminster, Lambeth, London, Greater London, England, SW1A 2AA, United Kingdom","center":["51.50344025","-0.12770820958562096"],"geometry":{"coordinates":["51.50344025","-0.12770820958562096"]}},"attribution":"https:\/\/locationiq.com\/attribution"}');
      $yourOutput = $this->invoker->invokeMethod($objMock, "mainProcess", [$decode, $query , $error, $errorCode]);
      print_r($yourOutput);
      $expectedOutput = '{"type":"FeatureCollection","query":["-0.12770820958562096","51.50344025"],"features":{"id":"352631455","type":"Feature","place-type":[null],"properties":[],"bbox":["51.5032573","51.5036483","-0.1278356","-0.1273038"],"text":"","place_name":"10 Downing Street, 10, Downing Street, Westminster, Lambeth, London, Greater London, England, SW1A 2AA, United Kingdom","center":["51.50344025","-0.12770820958562096"],"geometry":{"coordinates":["51.50344025","-0.12770820958562096"]}},"attribution":"https:\/\/locationiq.com\/attribution"}';
      $this->assertEquals($yourOutput, $expectedOutput);

    }
}