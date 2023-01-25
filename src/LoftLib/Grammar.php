<?php
namespace AKlump\LoftLib\Code;

/**
 * @brief Utility methods for working with English grammer (pluralization, singularization).
 */
class Grammar {

  const VOWEL     = 1;
  const CONSONANT = 2;

  /**
   * Tests if a word ends with ??.
   *
   * @param  string $word
   * @param  int $type One of the class constants.
   *
   * @return bool
   */
  public static function endsWith($word, $type) {
    $letter  = substr($word, -1);
    $letters = static::lettersByType($type);

    return in_array($letter, $letters);
  }

  /**
   * Insures a noun is in it's plural form.
   *
   * @param  string $noun
   *
   * @return string
   */
  public static function plural($noun) {
    if (!static::isPlural($noun)) {
      $noun = static::_plural($noun);
    }

    return $noun;
  }
  
  /**
   * Insures a noun is in it's singular form.
   *
   * @param  string $noun
   *
   * @return string
   */
  public static function singular($noun) {
    if (static::isPlural($noun)) {
      $noun = static::_singular($noun);
    }

    return $noun;
  }

  protected static function _singular($noun) {

    $irregulars = array_flip(static::irregularPlurals());
    if (isset($irregulars[$noun])) {
      return $irregulars[$noun];
    }

    if (($singular = preg_replace('/ies$/', 'y', $noun))
      && $singular !== $noun
      && ($noun === static::plural($singular))) {
      
      return $singular;
    }

    if (($singular = preg_replace('/ves$/', 'f', $noun))
      && $singular !== $noun
      && ($noun === static::plural($singular))) {
      
      return $singular;
    }
    
    return rtrim($noun, 's');
  }


  protected static function _plural($noun) {

    $irregulars = static::irregularPlurals();
    if (isset($irregulars[$noun])) {
      return $irregulars[$noun];
    }

    if (preg_match('/((.+)(.))y$/', $noun, $matches)) {
      // If ends in y after a consonant turn to i and add es
      if (static::endsWith($matches[3], static::CONSONANT)) {
        return $matches[1] . 'ies';
      }
    }

    // Words ending in -f or -fe
    if (preg_match('/(.+)fe?$/', $noun, $matches) && !in_array($noun, static::wordsEndingInFInPlural())) {
      return $matches[1] . 'ves';
    }
    
    return $noun . 's';
  }

  /**
   * Returns an array of words ending in f that should not end with -ves in plural.
   *
   * @return array
   */
  protected static function wordsEndingInFInPlural() {
    return array('roof', 'cliff', 'sheriff');
  }

  /**
   * Return an array of nouns that are irregular in their plural.
   *
   * @return array Keys are the singular, values are the plural.
   */
  public static function irregularPlurals() {
    return array(
      'man' => 'men',
      'woman' => 'women',
      'child' => 'children',
      'mouse' => 'mice',
      'tooth' => 'teeth',
      'goose' => 'geese',
      'foot' => 'feet',
      'ox' => 'oxen',
    );
  }

  public static function isPlural($noun) {
    if (in_array($noun, static::irregularPlurals())) {
      return TRUE;
    }

    return (bool) preg_match('/e?s$/', $noun, $matches);
  }

  public static function letters() {
    $codes = array_combine(range(97, 122), range(97, 122));
    foreach ($codes as $key => $code) {
      $codes[$key] = chr($code);
    }
    return $codes;
  }
  
  public static function consonants() {
    return array_diff(static::letters(), static::vowels());
  }

  public static function vowels() {
    return array_intersect(static::letters(), array('a', 'e', 'i', 'o', 'u'));
  }
  
  protected static function lettersByType($type) {
    switch ($type) {
      case static::VOWEL:
        return static::vowels();
      case static::CONSONANT:
        return static::consonants();
      default:
        return array();
    }
  }  
  
}
