<?php
/**
 * @file
 * PHPUnit tests for the Object class
 *
 */

namespace AKlump\Http\CollectionJson;
require_once dirname(__FILE__) . '/../../../../vendor/autoload.php';

class ObjectTest extends \PHPUnit_Framework_TestCase {

  public function testErrorImport() {
    $control = '{"collection":{"version":"1.0","href":"","error":{"title":"Not Found","code":"404","message":"Resource not found."}}}';
    $obj = Object::import($control);
    $this->assertSame($control, strval($obj));
  }

  /**
   * Provides data for testImportStrings.
   *
   * @return 
   *   - 0: Object $model
   *   - 1: string JSON
   */
  function importStringsProvider() {
    $return     = array();

    $data       = new Data('title', 'CollectionJson', 'Title');
    $dataArray  = array(
      $data,
      new Data('author', 'Aaron Klump', 'Author'),
    );
    
    $link       = new Link('http://www.intheloftstudios.com/collection-json', 'item');
    
    $item       = new Item('http://www.intheloftstudios.com/api/1.0/packages', $dataArray, array($link));

    $template   = new Template(array(
      new Data('title', '', 'Title'),
      new Data('author', '', 'Author'),
    ));

    $queryArray = array(
      new Data('search', ''),
    );
    $query    = new Query('http://www.intheloftstudios.com/api/1.0/packages', $queryArray, 'collection', 'search');

    $error = new Error(403);

    $collection = new Collection('http://www.intheloftstudios.com/api/1.0/packages');
    $collection
    ->setTemplate($template)
    ->addQuery($query)
    ->addItem($item)
    ->setLInks(array($link));

    $return[] = array($data, strval($data));
    $return[] = array($error, strval($error));
    $return[] = array($item, strval($item));
    $return[] = array($link, strval($link));
    $return[] = array($query, strval($query));
    $return[] = array($template, strval($template));
    $return[] = array($collection, strval($collection));

    return $return;
  }
  
  /**
   * @dataProvider importStringsProvider 
   */
  public function testImportStrings($model, $json) {
    $class = get_class($model);
    $obj = Object::import($json);
    $this->assertInstanceOf($class, $obj);
    $this->assertSame($json, strval($obj));

    $obj = Object::import($json, $model->flush());
    $this->assertInstanceOf($class, $obj);
    $this->assertSame($json, strval($obj));
  }
  
  /**
   * @expectedException \Exception
   */
  public function testValidJsonButCannotUnderstandException() {
    Object::import('["do","re","mi"]');
  } 

  /**
   * @expectedException \Exception
   * @expectedExceptionMessage Import source is not valid JSON.
   */
  public function testNotJsonStringImportException() {
    Object::import('{"href:');
  } 
}