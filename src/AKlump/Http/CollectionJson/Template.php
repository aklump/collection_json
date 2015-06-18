<?php
namespace AKlump\Http\CollectionJson;
use \AKlump\Http\Transfer\PayloadInterface;

/**
 * Represents a template
 *
 * This is a standalone object, as is Collection.
 */
class Template extends Object implements PayloadInterface {
  
  public function __construct($dataArray = array()) {
    parent::__construct();
    $this->setDataArray($dataArray);
  }

  public function asStdClass() {
    $obj = new \stdClass;
    foreach ($this->getDataArray() as $data) {
      $obj->data[] = $data->asStdClass();
    }
    
    return (object) array('template' => $obj);
  }

  public function setContentType($mimeType) {
    return $this;
  }
  
  public function getContentType() {
    return 'application/vnd.collection+json';
  }  

  public function setContent($content) {
    $obj = json_decode((string) $content);

    // Allow the passing of a collection json; pulls out template.
    if (isset($obj->collection->template)) {
      $obj = $obj->collection;
    }

    if (isset($obj->template->data)) {
      $dataArray = array();
      foreach ($obj->template->data as $data) {
        $prompt = isset($data->prompt) ? $data->prompt : NULL;
        $dataArray[] = new Data($data->name, $data->value, $prompt);
      }
      $this->setDataArray($dataArray);
    }

    return $this;
  }
  
  public function getContent() {
    return $this->__toString();
  }
}