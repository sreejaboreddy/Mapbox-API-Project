<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "../../../MapboxResponseHandler.php";
require_once __DIR__ . "../../Invoker.php";

class MapboxGeoJsonCreationTest extends TestCase {
  public $invoker;

  protected function setUp() {
    $this->invoker = new Invoker();
  }
  /**
   * @test
   */
  public function testQueryForForwardGeocoding() {
    $obj = new MapboxResponseHandler();
    $decode = [
      "place_id" => "46622347",
      "licence" => "https://locationiq.com/attribution",
      "osm_type" => "node",
      "osm_id" => "3916613190",
      "boundingbox" => [
        "51.5237129",
        "51.5238129",
        "-0.1585243",
        "-0.1584243"
      ],
      "lat" => "51.5237629",
      "lon" => "-0.1584743",
      "display_name" => "Sherlock Holmes Museum, 221b, Baker Street, Marylebone, London, Greater London, England, NW1 6XE, United Kingdom",
      "class" => "tourism",
      "type" => "museum",
      "importance" => 0.840064245847336,
      "icon" => "https://locationiq.org/static/images/mapicons/tourist_museum.p.20.png"
    ];
    $query = ['q' => '221b, Baker St, London'];
    $objMock = \Mockery::mock('MapboxResponseHandler[featureExtraction,queryExtraction]', []);
    $objMock->shouldReceive('queryExtraction()')->with(['q' => '221b, Baker St, London'])->andReturn(['221b,', 'Baker', 'St,', 'London']);
    $objMock->shouldReceive('featureExtraction()')->with(
      [
        "place_id" => "46622347",
        "licence" => "https://locationiq.com/attribution",
        "osm_type" => "node",
        "osm_id" => "3916613190",
        "boundingbox" => [
          "51.5237129",
          "51.5238129",
          "-0.1585243",
          "-0.1584243"
        ],
        "lat" => "51.5237629",
        "lon" => "-0.1584743",
        "display_name" => "Sherlock Holmes Museum, 221b, Baker Street, Marylebone, London, Greater London, England, NW1 6XE, United Kingdom",
        "class" => "tourism",
        "type" => "museum",
        "importance" => 0.840064245847336,
        "icon" => "https://locationiq.org/static/images/mapicons/tourist_museum.p.20.png"
      ]
    )->andReturn(
      [
        'id' => '',
        'type' => 'Feature',
        'place-type' => [],
        'relevance' => '0.84006424584734',
        'properties' => [],
        'bbox' => [
          '51.5237129',
          '51.5238129',
          '-0.1585243',
          '-0.1584243'
        ],
        'text' => '',
        'place_name' => 'Sherlock Holmes Museum, 221b, Baker Street, Marylebone, London, Greater London, England, NW1 6XE, United Kingdom',
        'center' => ['51.5237629', '-0.1584743'],

        'geometry' =>
        [
          'coordinates' => ['51.5237629', '-0.1584743']
        ]
      ]
    );
    $yourOutput = $this->invoker->invokeMethod($obj, "mapboxGeoJsonCreation", [$decode, $query]);
    $expectedOutput = '{"type":"FeatureCollection","query":["221b,","Baker","St,","London"],"features":{"id":"","type":"Feature","place-type":[],"relevance":0.840064245847336,"properties":[],"bbox":["51.5237129","51.5238129","-0.1585243","-0.1584243"],"text":"","place_name":"Sherlock Holmes Museum, 221b, Baker Street, Marylebone, London, Greater London, England, NW1 6XE, United Kingdom","center":["51.5237629","-0.1584743"],"geometry":{"coordinates":["51.5237629","-0.1584743"]}},"attribution":"https:\/\/locationiq.com\/attribution"}';
    $this->assertEquals($yourOutput, $expectedOutput);
  }

  /**
   * @test
   */
  public function testQueryForReverseGeocoding() {
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
    $obj = new MapboxResponseHandler();
    $query = ['lat'=>'51.50344025','lon'=>'-0.12770820958562096'];
    $objMock = \Mockery::mock('MapboxResponseHandler[mapboxGeoJsonCreation,featureExtraction,queryExtraction,requestResponse]', []);
    $objMock->shouldReceive('queryExtraction()')->with(['lat'=>'51.50344025','lon'=>'-0.12770820958562096'])->andReturn(['51.50344025','-0.12770820958562096']);
    $objMock->shouldReceive('featureExtraction()')->with(
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
    )->andReturn(
      [
        'id' => '352631455',
        'type' => 'Feature',
        'place-type' => [null],
        'properties' => [],
        'bbox' => ['51.5032573', '51.5036483', '-0.1278356', '-0.1273038'],
        'text' => '',
        'place_name' => '10 Downing Street, 10, Downing Street, Westminster, Lambeth, London, Greater London, England, SW1A 2AA, United Kingdom',
        'center' => ['51.50344025', '-0.12770820958562096'],
        'geometry' => [
          'coordinates' => ['51.50344025', '-0.12770820958562096']
        ]
      ]
    );
    $yourOutput = $this->invoker->invokeMethod($obj, "mapboxGeoJsonCreation", [$decode, $query]);
    $expectedOutput = '{"type":"FeatureCollection","query":["-0.12770820958562096","51.50344025"],"features":{"id":"352631455","type":"Feature","place-type":[null],"properties":[],"bbox":["51.5032573","51.5036483","-0.1278356","-0.1273038"],"text":"","place_name":"10 Downing Street, 10, Downing Street, Westminster, Lambeth, London, Greater London, England, SW1A 2AA, United Kingdom","center":["51.50344025","-0.12770820958562096"],"geometry":{"coordinates":["51.50344025","-0.12770820958562096"]}},"attribution":"https:\/\/locationiq.com\/attribution"}';
    $this->assertEquals($yourOutput, $expectedOutput);
  }
}
