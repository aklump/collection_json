<?php
namespace AKlump\Http\CollectionJson;

/**
 * Represents a query
 */
class Query extends CollectionBase {

  public function __construct($href, Array $dataArray, $rel = '', $prompt = '') {
    parent::__construct();
    $this->setHref($href);
    $this->setDataArray($dataArray);
    $this->setRel($rel);
    $this->setPrompt($prompt);
  }

  public function asStdClass(): \stdClass {
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
