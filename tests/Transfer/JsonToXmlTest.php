<?php
/**
 * @file
 * PHPUnit tests for the JsonToXml class
 */

namespace AKlump\Http\Transfer;

use \AKlump\Http\Transfer\Payload;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\Http\Transfer\JsonToXml
 */
final class JsonToXmlTest extends TestCase {

  /**
   * Provides data for testLoftLibIntegration.
   */
  public function dataForTestLoftLibIntegrationProvider() {
    $tests = [];
    $tests[] = ['myStringThing', 'my.string.thing'];
    $tests[] = ['myStringThing', 'my string thing'];
    $tests[] = ['myStringThing', 'my_string_thing'];
    $tests[] = ['myStringThing', 'my-string-thing'];
    $tests[] = ['myString', 'my.string'];
    $tests[] = ['myString', 'my string'];
    $tests[] = ['myString', 'my_string'];
    $tests[] = ['myString', 'my-string'];
    $tests[] = ['mYSTRING', 'MY.STRING'];
    $tests[] = ['mYSTRING', 'MY STRING'];
    $tests[] = ['mYSTRING', 'MY_STRING'];
    $tests[] = ['mYSTRING', 'MY-STRING'];

    return $tests;
  }

  /**
   * @dataProvider dataForTestLoftLibIntegrationProvider
   */
  public function testLoftLibIntegration(string $control, string $key) {
    $this->assertSame($control, JsonToXml::modifyXmlKey($key));
  }

  /**
   * Provides data for testTextXml.
   *
   * @return
   *   - 0:
   */
  function textXmlProvider() {
    $tests = array();

    $tests[] = array(
      'application/json',
      '{"oh.my.dear":{}}',
      "<?xml version=\"1.0\"?>\n<root><ohMyDear/></root>\n",
      array('keyFormat' => 'lowerCamel'),
    );

    $tests[] = array(
      'text/xml',
      '<root><do/></root>',
      '<root><do/></root>',
    );
    $tests[] = array(
      'application/json',
      '{"collection":{}}',
      "<?xml version=\"1.0\"?>\n<root><collection/></root>\n",
    );

    return $tests;
  }

  /**
   * @dataProvider textXmlProvider
   */
  public function testTextXml($mime, $in, $out, $options = NULL) {
    $in = new Payload($mime, $in);
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
