<?php
namespace AKlump\LoftLib\Code;

/**
 * @brief Utility methods for working with strings.
 */
class String {
  
  /**
   * Convert a hyphenated, underscored or camel-cased string to lower CamelCase.
   *
   * @param  string $phrase
   *
   * @return string
   */  
  public static function lowerCamel($phrase) {
    return lcfirst(self::noWhitespace(ucwords(self::words($phrase))));
  }

  /**
   * Convert a hyphenated, underscored or camel-cased string to upper CamelCase.
   *
   * @param  string $phrase
   *
   * @return string
   */  
  public static function upperCamel($phrase) {
    return self::noWhitespace(ucwords(self::words($phrase)));
  }

  /**
   * Convert a camel-cased, underscored or space-sep string to underscored.
   *
   * @param  string $phrase
   *
   * @return string
   */
  public static function underscore($phrase) {
    return preg_replace('/\s/', '_', self::words($phrase));
  }

  /**
   * Convert a camel-cased, underscored or space-sep string to lower-case, underscored.
   *
   * @param  string $phrase
   *
   * @return string e.g., some_var_name.
   */
  public static function lowerUnderscore($phrase) {
    return strtolower(self::underscore($phrase));
  }
    
  /**
   * Convert a camel-cased, underscored or space-sep string to upper-case, underscored.
   *
   * @param  string $phrase
   *
   * @return string e.g., MY_NICE_CONSTANT.
   */
  public static function upperUnderscore($phrase) {
    return strtoupper(self::underscore($phrase));
  }

  /**
   * Convert a camel-cased, underscored or space-sep string to hyphenated.
   *
   * @param  string $phrase
   *
   * @return string
   */
  public static function hyphen($phrase) {
    return preg_replace('/\s/', '-', self::words($phrase));
  } 

  /**
   * Convert a camel-cased, underscored or space-sep string to lower-case, hyphenated.
   *
   * @param  string $phrase
   *
   * @return string
   */
  public static function lowerHyphen($phrase) {
    return strtolower(preg_replace('/\s/', '-', self::words($phrase)));
  } 

  /**
   * Convert a hyphenated, underscored or camel-cased string into words.
   *
   * @param  string $phrase
   *
   * @return string
   */
  public static function words($phrase) {
    $phrase = preg_replace('/[\s\-_]/s', ' ', $phrase);
    $phrase = trim(preg_replace('/[A-Z]/', ' \0', $phrase));
    
    return self::rmRepeatedWhitespace($phrase);
  }

  /**
   * Replace all repeated whitespace with the first whitespace char.
   *
   * @param  string $phrase
   *
   * @return string
   */
  public static function rmRepeatedWhitespace($phrase) {
    return preg_replace('/(\s)\s+/s', '\1', $phrase);
  }
  
  /**
   * Remove all whitespace from a string.
   *
   * @param  string $phrase
   *
   * @return string
   */
  public static function noWhitespace($phrase) {
    return preg_replace('/\s+/s', '', $phrase);
  }
}
