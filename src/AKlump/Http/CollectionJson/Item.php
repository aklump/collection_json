<?php
namespace AKlump\Http\CollectionJson;

/**
 * Represents an item.
 */
class Item extends Object {
  public function __construct($href, $dataArray = array(), $links = array()) {
    parent::__construct();
    $this->setHref($href);
    $this->setDataArray($dataArray);
    $this->setLinks($links);
  }

  public function asStdClass() {
    $obj = new \stdClass;
    $obj->href = $this->getHref();
    foreach ($this->getDataArray() as $data) {
      $obj->data[] = $data->asStdClass();
    }
    foreach($this->getLinks() as $l) {
      $obj->links[] = $l->asStdClass();
    }

    return $obj;    
  }
}