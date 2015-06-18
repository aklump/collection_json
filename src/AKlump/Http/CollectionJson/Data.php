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
      'value' => $this->getValue(), 
    );
    if ($p = $this->getPrompt()) {
      $obj->prompt = $p;
    }

    return $obj;
  }
}