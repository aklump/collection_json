<?php
namespace AKlump\LoftLib\Api\CollectionJson;

/**
 * Represents a template
 *
 * This is a standalone object, as is Collection.
 */
class Template extends Object {
  
  public function __construct($dataArray) {
    $this->setDataArray($dataArray);
  }

  public function asStdClass() {
    $obj = new \stdClass;
    foreach ($this->getDataArray() as $data) {
      $obj->data[] = $data->asStdClass();
    }
    
    return (object) array('template' => $obj);
  }
}