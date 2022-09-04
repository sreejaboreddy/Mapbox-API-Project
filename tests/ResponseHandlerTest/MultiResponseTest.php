<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "../../../MapboxResponseHandler.php";
require_once __DIR__ . "../../Invoker.php";

class MultiResponseTest extends TestCase {
  public $invoker;

  protected function setUp() {
    $this->invoker = new Invoker();
  }


  public function testForForwardGeocoding() {
    $obj = new MapboxResponseHandler();
    $decode = [
      [
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
        ],
        [
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
          ],
          [
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
      ]
    ];
    $query =  $query = ['lat' => '51.50344025', 'lon' => '-0.12770820958562096'];
    $objMock = \Mockery::mock('MapboxResponseHandler[mapboxGeoJsonCreation]', []);
    $objMock->shouldReceive('mapboxGeoJsonCreation()')->with([$decode, $query])->andReturn('{"type":"FeatureCollection","query":["-0.12770820958562096","51.50344025"],"features":{"id":"352631455","type":"Feature","place-type":[null],"properties":[],"bbox":["51.5032573","51.5036483","-0.1278356","-0.1273038"],"text":"","place_name":"10 Downing Street, 10, Downing Street, Westminster, Lambeth, London, Greater London, England, SW1A 2AA, United Kingdom","center":["51.50344025","-0.12770820958562096"],"geometry":{"coordinates":["51.50344025","-0.12770820958562096"]}},"attribution":"https:\/\/locationiq.com\/attribution"}');
    $yourOutput = $this->invoker->invokeMethod($obj, "multiResponse", [$decode, $query]);
    $expectedOutput =
     [
      '{"type":"FeatureCollection","query":["-0.12770820958562096","51.50344025"],"features":{"id":"352631455","type":"Feature","place-type":[null],"properties":[],"bbox":["51.5032573","51.5036483","-0.1278356","-0.1273038"],"text":"","place_name":"10 Downing Street, 10, Downing Street, Westminster, Lambeth, London, Greater London, England, SW1A 2AA, United Kingdom","center":["51.50344025","-0.12770820958562096"],"geometry":{"coordinates":["51.50344025","-0.12770820958562096"]}},"attribution":"https:\/\/locationiq.com\/attribution"}',
      
    '{"type":"FeatureCollection","query":["-0.12770820958562096","51.50344025"],"features":{"id":"352631455","type":"Feature","place-type":[null],"properties":[],"bbox":["51.5032573","51.5036483","-0.1278356","-0.1273038"],"text":"","place_name":"10 Downing Street, 10, Downing Street, Westminster, Lambeth, London, Greater London, England, SW1A 2AA, United Kingdom","center":["51.50344025","-0.12770820958562096"],"geometry":{"coordinates":["51.50344025","-0.12770820958562096"]}},"attribution":"https:\/\/locationiq.com\/attribution"}',
    
    '{"type":"FeatureCollection","query":["-0.12770820958562096","51.50344025"],"features":{"id":"352631455","type":"Feature","place-type":[null],"properties":[],"bbox":["51.5032573","51.5036483","-0.1278356","-0.1273038"],"text":"","place_name":"10 Downing Street, 10, Downing Street, Westminster, Lambeth, London, Greater London, England, SW1A 2AA, United Kingdom","center":["51.50344025","-0.12770820958562096"],"geometry":{"coordinates":["51.50344025","-0.12770820958562096"]}},"attribution":"https:\/\/locationiq.com\/attribution"}'
     ];
    $this->assertEquals($yourOutput, $expectedOutput);
  }
}
