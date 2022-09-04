<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "../../../MapboxRequestHandler.php";
require_once __DIR__ . "../../Invoker.php";


class MapParametersToLIQFormatTest extends TestCase {
  public $invoker;
  protected function setUp() {
    $this->invoker = new Invoker();
  }

  public function testMaptoLIQ() {
    $parameters = [
      'worldview' => 'us',
      'access_token' => '45678uiok567uihg'
    ];
    $obj = new MapboxRequestHandler('');
    $yourOutput = $this->invoker->invokeMethod($obj, "mapParametersToLIQFormat", [$parameters]);
    $expectedOutput = ['format' => 'json', 'key' => '45678uiok567uihg'];
    $this->assertEquals($yourOutput, $expectedOutput);

    $parameters = [
      'limit' => '2',
      'worldview' => 'us',
      'access_token' => '45678uiok567uihg'
    ];
    $obj = new MapboxRequestHandler('');
    $yourOutput = $this->invoker->invokeMethod($obj, "mapParametersToLIQFormat", [$parameters]);
    $expectedOutput = ['format' => 'json', 'key' => '45678uiok567uihg', 'limit' => '2'];
    $this->assertEquals($yourOutput, $expectedOutput);

    $parameters = [
      'language' => 'en',
      'country' => '.at',
      'worldview' => 'us',
      'access_token' => '45678uiok567uihg'
    ];
    $obj = new MapboxRequestHandler('');

    $yourOutput = $this->invoker->invokeMethod($obj, "mapParametersToLIQFormat", [$parameters]);
    $expectedOutput = ['format' => 'json', 'accept-language' => 'en', 'countrycodes' => '.at', 'key' => '45678uiok567uihg'];
    $this->assertEquals($yourOutput, $expectedOutput);
  }
}
