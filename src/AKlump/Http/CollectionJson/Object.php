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
   * Checks to see if an object has a link by name.
   *
   * @param  string  $name
   *
   * @return boolean       
   */
  public function hasLinkByName($name) {
    foreach ($this->getLinks() as $link) {
      if ($link->getName() == $name) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * Returns a link object by name
   *
   * @param  string $name
   *
   * @return AKlump\Http\CollectionJson\Link
   *   If not found an empty link will be returned for chaining.
   */
  public function getLinkByName($name) {
    foreach ($this->getLinks() as $link) {
      if ($link->getName() == $name) {
        return $link;
      }
    }

    return new Link(NULL, NULL, NULL, NULL);
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
   * Checks if data exists by a given name string.
   *
   * @param  string  $name
   *
   * @return boolean       
   */
  public function hasDataByName($name) {
    foreach ($this->getDataArray() as $data) {
      if ($data->getName() === $name) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * Returns a data object by name
   *
   * @param  string $name
   *
   * @return AKlump\Http\CollectionJson\Data
   *   If the data doesn't exist an empty Data object will be returned for
   *   chaining purposes.
   */
  public function getDataByName($name) {
    foreach ($this->getDataArray() as $data) {
      if ($data->getName() === $name) {
        return $data;
      }
    }

    return new Data(NULL, NULL, NULL);
  }

  /**
   * Find item(s) based on data criteria.
   *
   * @param  array $query
   *   One or more name/value pairs to match on.  They will be combined
   *   with an AND join.  If you're looking for all items that have 'blond'
   *   as the value of a data item with the name 'hair_color', then you 
   *   would pass: array('hair_color' => 'blond').
   *
   * @return array
   */
  public function findItems($query) {
    $matches = array();
    foreach ($this->getItems() as $key => $item) {
      $hits = 0;
      foreach ($query as $name => $value) {
        if ($item->getDataByName($name)->getValue() == $value) {
          ++$hits;
        }
      }
      if ($hits == count($query)) {
        $matches[$key] = $item;
      }
    }

    return $matches;
  }

  /**
   * Return the first matched item.
   *
   * @param  array $query array
   *
   * @return Item
   *
   * @see findItems().
   */
  public function findFirstItem($query) {
    return ($i = $this->findItems($query)) ? reset($i) : NULL;
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