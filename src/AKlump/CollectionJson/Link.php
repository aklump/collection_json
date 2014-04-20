<?php
namespace AKlump\LoftLib\Api\CollectionJson;

/**
 * Represents a link
 */
class Link extends Object {
  
  public function __construct($href, $rel, $render = '', $name = '', $prompt = '') {
    $this->setHref($href);
    $this->setRel($rel);
    $this->setName($name);
    $this->setPrompt($prompt);
    $this->setRender($render);
  }

  public function asStdClass() {
    $obj = (object) array(
      'href' => $this->getHref(), 
      'rel' => $this->getRel(), 
    );
    if ($n = $this->getName()) {
      $obj->name = $n;
    }
    if ($r = $this->getRender()) {
      $obj->render = $r;
    }
    if ($p = $this->getPrompt()) {
      $obj->prompt = $p;
    }

    return $obj;
  }
}