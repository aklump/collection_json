<?php
namespace AKlump\Http\CollectionJson;

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

  /**
   * Set the render.
   *
   * @param string $render
   *
   * @return $this
   */
  public function setRender($render) {
    $this->data['render'] = (string) $render;
  
    return $this;
  }

  /**
   * Return the render.
   *
   * @return string
   */  
  public function getRender() {
    return $this->data['render'];
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