<?php
namespace AKlump\Http\CollectionJson;

/**
 * Represents a data unit.
 */
class Data extends Object {
  
  public function __construct($name, $value, $prompt = '') {
    parent::__construct();
    $this->setName($name);
    $this->setValue($value);
    $this->setPrompt($prompt);
  }

  public function asStdClass() {
    $obj = (object) array(
      'name' => $this->getName(),
    );
    if ($p = $this->getPrompt()) {
      $obj->prompt = $p;
    }
    $obj->value = $this->getValue();

    $this->handleValues($obj->value);

    return $obj;
  }

  /**
   * Recursively process the $value and convert to stdClass
   *
   * @param  mixed &$value
   *   - array
   *   - object with method asStdClass
   *   - ...
   *
   * @return \stdClass
   */
  protected function handleValues(&$value) {
    if (is_array($value)) {
      foreach ($value as &$v) {
        $this->handleValues($v);
      }
    }
    elseif (method_exists($value, 'asStdClass')) {
      $value = $value->asStdClass();
    }
    return $value;
  }  
}