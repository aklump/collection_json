<?php
namespace AKlump\Http\CollectionJson;

/**
 * Represents the base class of shared methods for CollectionJson objects.
 */
abstract class Object {

  protected $data = array(
    'href' => '',
    'links' => array(),
    'name' => '',
    'value' => NULL,
    'prompt' => '',
    'data' => array(),
    'rel' => '',
    'items' => array(),
  );

  /**
   * Set the items array.
   *
   * @param array $items
   *
   * @return $this
   */
  public function setItems($items) {
    $this->data['items'] = array();
    foreach($items as $item) {
      $this->addItem($item);
    }
  
    return $this;
  }

  /**
   * Adds a single item.
   *
   * @param Item $item
   *
   * @return $this
   */  
  public function addItem(Item $item) {
    $this->data['items'][] = $item;
  
    return $this;
  }
  
  /**
   * Return the items array.
   *
   * @return array
   */  
  public function getItems() {
    return $this->data['items'];
  }

  /**
   * Set the rel.
   *
   * @param string $rel
   *
   * @return $this
   */
  public function setRel($rel) {
    $this->data['rel'] = (string) $rel;
  
    return $this;
  }

  /**
   * Return the rel.
   *
   * @return string
   */  
  public function getRel() {
    return $this->data['rel'];
  }

  public function setLinks($links) {
    $this->data['links'] = array();
    foreach($links as $link) {
      $this->addLink($link);
    }
  
    return $this;
  }
  
  public function addLink(Link $link) {
    $this->data['links'][] = $link;
  
    return $this;
  }
  
  public function getLinks() {
    return $this->data['links'];
  }

  /**
   * Set the prompt.
   *
   * @param string $prompt
   *
   * @return $this
   */
  public function setPrompt($prompt) {
    $this->data['prompt'] = (string) $prompt;
  
    return $this;
  }
  
  /**
   * Return the prompt.
   *
   * @return string
   */  
  public function getPrompt() {
    return $this->data['prompt'];
  }
  
  public function setHref($href) {
    $this->data['href'] = (string) $href;
  
    return $this;
  }
  
  public function getHref() {
    return $this->data['href'];
  }

  /**
   * Set the value.
   *
   * @param mixed $value
   *
   * @return $this
   */
  public function setValue($value) {
    $this->data['value'] = $value;
  
    return $this;
  }

  /**
   * Return the value.
   *
   * @return mixed
   */  
  public function getValue() {
    return $this->data['value'];
  }
  
  /**
   * Set the name.
   *
   * @param string $name
   *
   * @return $this
   */  
  public function setName($name) {
    $this->data['name'] = (string) $name;
  
    return $this;
  }

  /**
   * Return the name.
   *
   * @return string
   */  
  public function getName() {
    return $this->data['name'];
  }
  
  /**
   * Set the data array.
   *
   * @param array $data
   *
   * @return $this
   */  
  public function setDataArray($data) {
    $this->data['data'] = array();
    foreach($data as $data) {
      $this->addData($data);
    }
  
    return $this;
  }
  
  /**
   * Adds a single data.
   *
   * @param Data $data
   *
   * @return $this
   */  
  public function addData(Data $data) {
    $this->data['data'][] = $data;
  
    return $this;
  }
  
  /**
   * Return the data array.
   *
   * @return array
   */  
  public function getDataArray() {
    return $this->data['data'];
  }

  /**
   * Returns a stdClass object of this class
   *
   * This is used for nesting objects.
   *
   * @return object
   */
  //abstract protected function asStdClass();

  public function __toString() {
    return json_encode($this->asStdClass());
  }
}