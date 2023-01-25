<?php

namespace AKlump\Http\Transfer;

use AKlump\Http\Transfer\ContentTypeTranslaterInterface;
use AKlump\Http\Transfer\PayloadInterface;
use AKlump\Http\Transfer\Payload;
use Spatie\ArrayToXml\ArrayToXml;
use AKlump\LoftLib\Code\Strings;
use AKlump\LoftLib\Code\Grammar;


/**
 * Converts json to xml and keys to lowerCamelCase.
 *
 * If you wish different key handling then just extend this class and modify
 * the modifyXmlKey method.
 */
class JsonToXml extends ContentTypeTranslator {

  /**
   * Translate from json to xml
   *
   * @param PayloadInterface $payload
   */
  public static function translate(PayloadInterface $payload) {

    switch ($payload->getContentType()) {
      case 'text/xml':
      case 'application/xml':
        return clone $payload;

      case 'application/json':
        $obj = new Payload('text/xml');
        $data = json_decode($payload->getContent(), TRUE);
        static::insureValidKeys($data);
        $xml = ArrayToXml::convert($data);
        $obj->setContent($xml);

        return $obj;

      default:
        return static::failedTranslation($payload);
    }
  }

  /**
   * Converts a string to lower camel case.
   *
   * my.string -> myString
   * my string -> myString
   * my_string -> myString
   * my-string -> myString
   *
   * EXTEND THIS METHOD FOR DIFFERENT KEY HANDLING.
   *
   * @param string $key
   *
   * @return string The converted string
   */
  public static function modifyXmlKey($key) {
    $key = preg_replace('/[ _\-.]/', ' ', $key);
    $key = ucwords($key);
    $key = str_replace(' ', '', $key);
    $key = strtolower(substr($key, 0, 1)) . substr($key, 1);

    return $key;
  }


  /**
   * Recursively process an array's keys passing each to modifyXmlKey().
   *
   * @param array &$data
   */
  protected static function insureValidKeys(&$data) {
    foreach (array_keys($data) as $key) {
      if (is_string($key)) {
        if ($key !== ($newKey = static::modifyXmlKey($key))) {
          static::swapKey($data, $key, $newKey);
          $key = $newKey;
        }
      }
      if (is_array($data[$key])) {
        static::insureValidKeys($data[$key]);
      }
    }
  }

  /**
   * Replaces an array's key with a new one, not loosing ordre.
   *
   * @param array &$array
   * @param string $old_key
   * @param string $new_key
   */
  protected static function swapKey(&$array, $old_key, $new_key) {
    $keys = array_keys($array);
    $index = array_search($old_key, $keys);
    if ($index !== FALSE) {
      $keys[$index] = $new_key;
      $array = array_combine($keys, $array);
    }
  }
}
