<?php
/**
 * @file
 * PHPUnit tests for the JsonToXml class
 */
namespace AKlump\Http\Transfer;
use \AKlump\Http\Transfer\Payload;

require_once dirname(__FILE__) . '/../../../../vendor/autoload.php';

class JsonToXmlTest extends \PHPUnit_Framework_TestCase {

  /**
   * Provides data for testTextXml.
   *
   * @return 
   *   - 0: 
   */
  function textXmlProvider() {
    $tests = array();

    $tests[] = array(
      'application/json', '{"oh.my.goodness":{}}', "<?xml version=\"1.0\"?>\n<root><ohMyGoodness/></root>\n", array('keyFormat' => 'lowerCamel')
    );

    $tests[] = array(
      'text/xml', '<root><do/></root>', '<root><do/></root>',
    );
    $tests[] = array(
      'application/json', '{"collection":{}}', "<?xml version=\"1.0\"?>\n<root><collection/></root>\n",
    );
    
    return $tests;
  }
  
  /**
   * @dataProvider textXmlProvider 
   */   
  public function testTextXml($mime, $in, $out, $options = NULL) {
    $in         = new Payload($mime, $in);
    $translated = JsonToXml::translate($in, $options);
    $this->assertSame($out, $translated->getContent());
  }

  public function testFailsTextPlain() {
    $in = new Payload('text/plain', 'do re mi');
    $out = JsonToXml::translate($in);
    $this->assertSame('Unable to recognize/translate mime type: text/plain', $out->getContent());
    $this->assertSame('text/error', $out->getContentType());
  }
  
}
