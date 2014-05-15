<?php
/**
 * @file
 * PHPUnit tests for the Item class
 *
 */

namespace AKlump\Http\CollectionJson;
require_once dirname(__FILE__) . '/../vendor/autoload.php';

class ItemTest extends \PHPUnit_Framework_TestCase {

  public function testGetData() {
    $obj = new Item('http://www.site.com/api/1.0/users/5', array(
      new Data('do', 'a deer', 'First key'),
      new Data('re', 'a drop', 'Second key'),
      new Data('mi', 'a name', 'Third key'),
    ));

    $this->assertTrue($obj->hasDataByName('do'));
    $this->assertTrue($obj->hasDataByName('re'));
    $this->assertTrue($obj->hasDataByName('mi'));
    $this->assertFalse($obj->hasDataByName('fa'));

    $this->assertSame('a deer', $obj->getDataByName('do')->getValue());
    $this->assertSame('a drop', $obj->getDataByName('re')->getValue());
    $this->assertSame('a name', $obj->getDataByName('mi')->getValue());
    $this->assertSame(NULL, $obj->getDataByName('fa')->getValue());

    $this->assertSame('First key', $obj->getDataByName('do')->getPrompt());
    $this->assertSame('Second key', $obj->getDataByName('re')->getPrompt());
    $this->assertSame('Third key', $obj->getDataByName('mi')->getPrompt());
    $this->assertSame('', $obj->getDataByName('fa')->getPrompt());
  }
}