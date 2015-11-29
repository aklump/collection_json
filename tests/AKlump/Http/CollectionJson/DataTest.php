<?php
/**
 * @file
 * PHPUnit tests for the Data class
 */

namespace AKlump\Http\CollectionJson;
require_once dirname(__FILE__) . '/../../../../vendor/autoload.php';

class DataTest extends \PHPUnit_Framework_TestCase {
  
  public function testProcessesArraySecondLevelIn() {
    $value = array(
      new Data('do', 'C', 'Do'),
      new Data('re', 'D', 'Re'),
    );
    $value = new Data('levelOne', $value, 'LevelOne');
    $obj = new Data('name', $value, 'Label');
    $this->assertSame('{"name":"name","value":{"name":"levelOne","value":[{"name":"do","value":"C","prompt":"Do"},{"name":"re","value":"D","prompt":"Re"}],"prompt":"LevelOne"},"prompt":"Label"}', strval($obj));

    $this->assertEquals(json_decode('{"name":"name","value":{"name":"levelOne","value":[{"name":"do","value":"C","prompt":"Do"},{"name":"re","value":"D","prompt":"Re"}],"prompt":"LevelOne"},"prompt":"Label"}'), $obj->asStdClass());
  }
  
  public function testProcessesFourLevelsOfObjects() {
    $value = new Data('four', 4);
    $value = new Data('three', $value);
    $value = new Data('two', $value);
    $value = new Data('one', $value);
    $obj = new Data('name', $value, 'Label');
    $this->assertSame('{"name":"name","value":{"name":"one","value":{"name":"two","value":{"name":"three","value":{"name":"four","value":4}}}},"prompt":"Label"}', strval($obj));
  }
  
  public function testProcessesArrayOneLevel() {
    $value = array(
      new Data('do', 'C', 'Do'),
      new Data('re', 'D', 'Re'),
    );
    $obj = new Data('name', $value, 'Label');
    $this->assertSame('{"name":"name","value":[{"name":"do","value":"C","prompt":"Do"},{"name":"re","value":"D","prompt":"Re"}],"prompt":"Label"}', strval($obj));
  }

  public function testSimpleData() {
    $obj = new Data('name', 'value', 'Label');
    $this->assertSame('{"name":"name","value":"value","prompt":"Label"}', strval($obj));
  }
}
