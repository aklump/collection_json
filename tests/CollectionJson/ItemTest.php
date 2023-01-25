<?php
/**
 * @file
 * PHPUnit tests for the Item class
 *
 */

namespace AKlump\Http\CollectionJson;
require_once dirname(__FILE__) . '/../../../../vendor/autoload.php';

class ItemTest extends \PHPUnit_Framework_TestCase {

  /**
   * A query yielding no results.
   */
  public function testFindItemNoResult() {
    $obj    = $this->collection;
    $items  = $obj->getItems();
    $query  = array('resource' => 'apple');
    
    $result = $obj->findItems($query);
    $this->assertSame(array(), $result);
    $this->assertCount(0, $result);
    $this->assertSame(NULL, $obj->findFirstItem($query));
  }

  /**
   * A query with two criteria returns one record.
   */
  public function testFindItemTwoQueries() {
    $obj    = $this->collection;
    $items  = $obj->getItems();
    $query  = array('resource' => 'cycle', 'id' => 2);
    
    $result = $obj->findItems($query);
    $this->assertSame($items[2], reset($result));
    $this->assertSame($items[2], $result[2]);
    $this->assertCount(1, $obj->findItems($query));

    $this->assertSame($items[2], $obj->findFirstItem($query));
  }

  /**
   * A query returns two records.
   */
  public function testFindItemTwoItems() {
    $obj    = $this->collection;
    $items  = $obj->getItems();
    $query  = array('resource' => 'cycle');
    

    $result = $obj->findItems($query);
    $this->assertSame($items[1], reset($result));
    $this->assertSame($items[1], $result[1]);
    $this->assertSame($items[2], $result[2]);
    $this->assertCount(2, $obj->findItems($query));

    $this->assertSame($items[1], $obj->findFirstItem($query));
  }

  /**
   * A query with one criteria returns one record.
   */
  public function testFindItemSingleItem() {
    $obj    = $this->collection;
    $items  = $obj->getItems();
    $query  = array('resource' => 'user');
    $this->assertSame($items[0], $obj->findFirstItem($query));
    $found = $obj->findItems($query);
    $this->assertSame($items[0], reset($found));
    $this->assertCount(1, $obj->findItems($query));
  }

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

  public function setUp() {
    $this->collection = new Collection;
    $this->collection->setContent('{"collection":{"href":"https://dev.ovagraph.local/api/3/changes","items":[{"data":[{"name":"resource","prompt":"Resource","value":"user"},{"name":"id","prompt":"Id","value":22264},{"name":"modified","prompt":"Modified","value":1301782272},{"name":"status","prompt":"Status","value":1}],"href":"https://dev.ovagraph.local/api/3/user/22264"},{"data":[{"name":"resource","prompt":"Resource","value":"cycle"},{"name":"id","prompt":"Id","value":1},{"name":"modified","prompt":"Modified","value":1301782272},{"name":"status","prompt":"Status","value":1}],"href":"https://dev.ovagraph.local/api/3/cycle/1"},{"data":[{"name":"resource","prompt":"Resource","value":"cycle"},{"name":"id","prompt":"Id","value":2},{"name":"modified","prompt":"Modified","value":1301782272},{"name":"status","prompt":"Status","value":1}],"href":"https://dev.ovagraph.local/api/3/cycle/2"},{"data":[{"name":"resource","prompt":"Resource","value":"day"},{"name":"id","prompt":"Id","value":1},{"name":"modified","prompt":"Modified","value":1301782272},{"name":"status","prompt":"Status","value":1}],"href":"https://dev.ovagraph.local/api/3/day/1"}],"version":"1.0"}}');    
  }
}