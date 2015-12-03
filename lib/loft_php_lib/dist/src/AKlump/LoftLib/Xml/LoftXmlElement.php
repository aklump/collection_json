<?php
/**
 * @file
 * Defines the LoftXmlElement class.
 *
 * This class does not use namespaces and therefor works php < 5.3
 *
 * @ingroup loft_xml
 * @{
 */

/**
 * Represents an extension of the simpleXmlElement class.
 * 
 * @brief Enhances Php's simpleXmlElement with better cdata handling and
 * other fancy stuff.
 */
class LoftXmlElement extends simpleXMLElement {
  /**
   * Do not access this variable; use the set/getConfig methods instead.
   *
   * @var array
   * - autoEntities bool Set this to TRUE to convert <, >, & during addChild()
   * to their html entities.
   * - autoCData bool Set to TRUE to auto-wrap with CDATA.
   */
  public static $config = array();

  /**
   * Returns a string (or node value) wrapped in CDATA.
   *
   * @code
   *   $xml->addChild('title', '<![CDATA[<h1>My Book</h1>]]>');
   *   $xml->cdata($xml->title);
   * @endcode
   *
   * @param string|XmlFieldXmlElement $key
   */
  public function cdata($key = NULL, $force = FALSE) {
    $value = (string) $key;
    self::wrapCData($value, $force);

    return $value;
  }

  /**
   * Adds a child, wrapping it in CDATA if needed.
   *
   * @param string $name
   * @param string $value
   * @param string $namespace [description]
   */
  public function addCDataChild($name, $value, $namespace = NULL) {
    $this->wrapCData($value, TRUE);
    
    return $this->addChild($name, $value, $namespace);
  }

  /**
   * Add a child element; this corrects CDATA issues
   *
   * @throws RuntimeException
   */
  public function addChild($name, $value = NULL, $namespace = NULL) {
    
    if ($this->isCData($value)

      // Automatic CDATA handling if enabled.
      || ($this->getConfig('autoCData', FALSE) && $this->wrapCData($value))) {
        // Stash a CDATA value for later...
        $valueAsCData = $value;
        $value = NULL;
    }

    // If not cdata then we need to look into escaping entities.
    elseif ($this->getConfig('autoEntities', FALSE)) {
      $this->xmlChars($value);
    }

    $child = parent::addChild($name, $value, $namespace);
    
    // switch (func_num_args()) {
    //   case 1:
    //     $child = parent::addChild($name);
    //     break;

    //   case 2:
    //     $child = parent::addChild($name, $value);
    //     break;

    //   case 3:
    //     $child = parent::addChild($name, $value, $namespace);
    //     break;
    // }      

    if (isset($valueAsCData)) {
      $node  = dom_import_simplexml($child);
      $no    = $node->ownerDocument;
      $value = self::stripCData($valueAsCData);
      $node->appendChild($no->createCDATASection($value));
    }

    return $child;
  }

  /**
   * Adds a time value as a child and properly formats the time.
   *
   * @param string $name
   * @param mixed $value This is sent to DateObject class.
   * @param string $namespace 
   *
   * @see  DateObject
   *
   * This function depends on the date module being enabled.
   *
   * @see  https://www.drupal.org/project/date
   */
  public function addDateChild($name, $value, $namespace = NULL) {
    $value = $value instanceof DateObject ? $value : new DateObject($value);

    return $this->addChild($name, $value->format('r'), $namespace);
  }

  /**
   * Extends to allow chaining of add attribute
   *
   * e.g. $xml->addAttribute('size', 'large')->addAttribute('color', 'blue')
   */
  public function addAttribute($name, $value = NULL, $namespace = NULL) {
    parent::addAttribute($name, $value, $namespace);
    // switch (func_num_args()) {
    //   case 1:
    //     parent::addAttribute($name);
    //     break;
    //   case 2:
    //     parent::addAttribute($name, $value);
    //     break;
    //   case 3:
    //     parent::addAttribute($name, $value, $namespace);
    //     break;
    // }

    return $this;
  }

  // /**
  //  * Renders to xml, counterpart to the CDATA issue
  //  */
  // public function asXML($filename = NULL) {
  //   switch (func_num_args()) {
  //     case 0:
  //       $string = parent::asXML();
  //       break;

  //     case 1:
  //       $string = parent::asXML($filename);
  //       break;
  //   }

  //   return $string;
  // }

  public static function setConfig($name, $value) {
    self::$config[$name] = $value;
  }

  public static function getConfig($name, $default = NULL) {
    $value = isset(self::$config[$name]) ? self::$config[$name] : $default;

    return $value;
  }

  /**
   * Modifies a string by wrapping the CDATA when needed.
   *
   * @param  string &$value
   * @param  bool $force Wrap the CDATA without analyzing the contents.
   *
   * @return  bool If a transformation took place.
   */
  public static function wrapCData(&$value, $force = FALSE) {
    if (!($apply = $force) && $value && !self::isCData($value)) {
      
      // First convert everything to the allowed xml chars.
      $analyzed = $value;
      self::xmlChars($analyzed);
      $analyzed = preg_replace('/&lt;|&amp;|&gt;|&quot;|&apos/', '', $analyzed);

      // Then strip them out and look for &, which means we have other entities.
      if (strpos($analyzed, '&') !== FALSE) {
        $apply = TRUE;
      }

      // Test to see if we have balanced html tags...
      $regex = "/<\/?\w+((\s+\w+(\s*=\s*(?:\".*?\"|'.*?'|[^'\">\s]+))?)+\s*|\s*)\/?>/is";
      if (!$apply && preg_match($regex, $value)) {
        $apply = TRUE;
      }
    }

    if (!empty($apply)) {
      $value = "<![CDATA[{$value}]]>";
    }

    return $apply;
  }

  /**
   * Tests a string to know if it's already wrapped in CData.
   *
   * @param  string $value
   *
   * @return boolean
   */
  public static function isCData($value) {
    return strpos($value, '<![CDATA[') !== FALSE;
  }

  /**
   * Converts the 5 xml chars to their html entities.
   *
   * @param  string &$value
   *
   * @return bool If the string was altered. Note: &#039; is converted to
   *   &apos; and is NOT considered altered.
   */
  public static function xmlChars(&$value) {
    $value = str_replace('&#039;', '&apos;', $value);
    $original = $value;
    $analyzed = htmlspecialchars($value, ENT_QUOTES, NULL, FALSE);
    $analyzed = str_replace('&#039;', '&apos;', $analyzed);
    if ($changed = $analyzed !== $original) {
      $value = $analyzed;  
    }
    
    return $changed;
  }  

  /**
   * Returns a string with the CDATA section removed.
   *
   * @param  string $value
   *
   * @return string
   */
  public static function stripCData($value) {
    if (preg_match("/<!\[CDATA\[(.*)\]\]>/s", $value, $matches)) {
      return isset($matches[1]) ? $matches[1] : $matches[0];
    }

    return $value;
  }
  
}
