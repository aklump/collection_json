<?php
namespace AKlump\Http\CollectionJson;

/**
 * Represents an item.
 */
class Item extends CollectionBase {
  public function __construct($href, Array $dataArray = NULL, Array $links = NULL) {
    parent::__construct();
    $this->setHref($href);
    $this->setDataArray($dataArray);
    $this->setLinks($links);
  }

  public function asStdClass(): \stdClass {
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
