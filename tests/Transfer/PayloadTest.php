<?php
/**
 * @file
 * PHPUnit tests for the Payload class
 *
 */

namespace AKlump\Http\Transfer;
require_once dirname(__FILE__) . '/../../../../vendor/autoload.php';

class PayloadTest extends \PHPUnit_Framework_TestCase {

  public function testGetSet() {
    $obj = new Payload('application/json');

    $obj = new Payload('application/json', '{"name":"aaron"}');
    $this->assertSame('application/json', $obj->getContentType());
    $this->assertSame('{"name":"aaron"}', $obj->getContent());

    $subject = <<<EOD
{ "collection" : 
  {
    "version" : "1.0",
    "href" : "http://example.org/friends/"
  } 
}    
EOD;
    $obj->setContentType('application/vnd.collection+json');
    $obj->setContent($subject);
    $this->assertSame('application/vnd.collection+json', $obj->getContentType());
    $this->assertSame($subject, $obj->getContent());
  }
}
