<?php
namespace AKlump\LoftLib\Api\CollectionJson;

/**
 * Represents a query
 */
class Query extends Object {
  
  public function __construct($href, $dataArray, $rel = '', $prompt = '') {
    $this->setHref($href);
    $this->setDataArray($dataArray);
    $this->setRel($rel);
    $this->setPrompt($prompt);
  }

  public function asStdClass() {
    $obj = (object) array(
      'href' => $this->getHref(), 
      'rel' => $this->getRel(), 
    );
    if ($p = $this->getPrompt()) {
      $obj->prompt = $p;
    }
    foreach ($this->getDataArray() as $data) {
      $obj->data[] = $data->asStdClass();
    }
    
    return $obj;
  }
}