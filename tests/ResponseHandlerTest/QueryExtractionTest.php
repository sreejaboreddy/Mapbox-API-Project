<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "../../../MapboxResponseHandler.php";
require_once __DIR__ . "../../Invoker.php";

class QueryExtractionTest extends TestCase {
    public $invoker;

    protected function setUp() {
        $this->invoker = new Invoker();
    }

    /**
     * @test
     */
    public function testQueryForForwardGeocoding() {
        $obj = new MapboxResponseHandler();
        $query = ['q' => 'chester'];
        $yourOutput = $this->invoker->invokeMethod($obj, "queryExtraction", [$query]);
        $expectedOutput = ['chester'];
        $this->assertEquals($yourOutput, $expectedOutput);


        $query = ['q' => '515 15th St NW, Washington, DC 20004'];
        $yourOutput = $this->invoker->invokeMethod($obj, "queryExtraction", [$query]);
        $expectedOutput =['515', '15th', 'St', 'NW,', 'Washington,', 'DC', '20004'];
        $this->assertEquals($yourOutput, $expectedOutput);
    }
    
    /**
     * @test
     */
    public function testQueryForReverseGeocoding() {
        $obj = new MapboxResponseHandler();
        $query = ['lat'=>'2345' , 'lon'=>'98765'];
        $yourOutput = $this->invoker->invokeMethod($obj, "queryExtraction", [$query]);
        $expectedOutput = ['98765', '2345'];
        $this->assertEquals($yourOutput, $expectedOutput);

        $query = ['lat'=>'2345' ];
        $yourOutput = $this->invoker->invokeMethod($obj, "queryExtraction", [$query]);
        $expectedOutput = [];
        $this->assertEquals($yourOutput, $expectedOutput);
    }
}
