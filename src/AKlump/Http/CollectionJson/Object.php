<?php
namespace AKlump\Http\CollectionJson;

/**
 * Represents the base class of shared methods for CollectionJson objects.
 */
abstract class Object {

  protected $data = array();

  public function __construct() {
    $this->flush();
  }
  
  /**
   * Flushes all data and leaves an empty container.
   *
   * @return $this
   */
  public function flush() {
    $this->data = array(
      'href' => '',
      'links' => array(),
      'name' => '',
      'value' => NULL,
      'prompt' => '',
      'data' => array(),
      'rel' => '',
      'items' => array(),
    );

    return $this;
  }


  /**
   * Set the items array.
   *
   * @param array $items
   *
   * @return $this
   */
  public function setItems(Array $items = NULL) {
    $this->data['items'] = array();
    if ($items) {
      foreach($items as $item) {
        $this->addItem($item);
      }
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

  public function setLinks(Array $links = NULL) {
    $this->data['links'] = array();
    if ($links) {
      foreach($links as $link) {
        $this->addLink($link);
      }
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
  public function setDataArray(Array $data = NULL) {
    $this->data['data'] = array();
    if ($data) {
      foreach($data as $data) {
        $this->addData($data);
      }
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
    // Do not alter this for pretty print, needs to happen elsewhere
    // due to the fact that we might use this statically and so there's
    // no way to set this->options or similar strategy.
    return json_encode($this->asStdClass());
  }

  protected static function checkKeys() {
    $args = func_get_args();
    $haystack = array_shift($args);
    foreach ($args as $key) {
      if (!array_key_exists($key, $haystack)) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * Tries to return an object based on a JSON string.
   *
   * @param  string $json
   * @param  Object $obj  If the type of object is known, and you want only
   * to extract the data from the json and set it on $obj, then provide
   * $obj here.  Otherwise the object will be guessed by analyzing the JSON.
   *
   * @return Object
   *
   * @see  Collection::setContent
   */
  public static function import($json, Object $obj = NULL) {
    if (!($data = json_decode($json))) {
      throw new \InvalidArgumentException("Import source is not valid JSON.");
    }

    $data = (array) $data;
    $understood = FALSE;

    // A "Collection" object represented by the JSON.
    if ($obj instanceof Collection || self::checkKeys($data, 'collection')) {
      $understood = TRUE;
      $data = (array) $data['collection'];
      $data += array('href' => '', 'items' => array(), 'links' => array(), 'queries' => array(), 'template' => '', 'version' => '1.0');

      $obj = isset($obj) ? $obj : new Collection('');
      $obj->setHref($data['href']);

      $obj->setItems(array());
      foreach ($data['items'] as $value) {
        $value = json_encode($value);
        $obj->addItem(Object::import($value, new Item(NULL)));
      }

      foreach ($data['links'] as $value) {
        $value = json_encode($value);
        $obj->addLink(Object::import($value, new Link(NULL, NULL)));
      }

      foreach ($data['queries'] as $value) {
        $value = json_encode($value);
        $obj->addQuery(Object::import($value, new Query(NULL, array())));
      }

      if (!empty($data['template'])) {
        $value = json_encode(array('template' => $data['template']));
        $obj->setTemplate($template = Object::import($value, new Template));  
      }

      if (!empty($data['version'])) {
        $obj->setVersion($data['version']);
      }

      if (!empty($data['error'])) {
        $value = json_encode($data['error']);
        $obj->setError(Object::import($value, new Error(NULL)));
      }
    }

    // A "Data" object.
    elseif ($obj instanceof Data || self::checkKeys($data, 'name', 'value')) {
      $understood = TRUE;
      $data += array('name' => '', 'value' => '','prompt' => '');

      $obj = isset($obj) ? $obj : new Data('', '');
      $obj->setName($data['name']);
      $obj->setValue($data['value']);
      $obj->setPrompt($data['prompt']);
    }

    // A "Query" object
    elseif ($obj instanceof Query || self::checkKeys($data, 'data', 'href', 'prompt')) {
      $understood = TRUE;
      $data += array('data' => array(), 'href' => '', 'prompt' => '', 'rel' => '');
      $dataArray  = array();
      foreach ($data['data'] as $value) {
        $value       = json_encode($value);
        $dataArray[] = Object::import($value);
      }
      
      $obj = isset($obj) ? $obj : new Query('', array());
      $obj->setHref($data['href']);
      $obj->setDataArray($dataArray);
      $obj->setRel($data['rel']);
      $obj->setPrompt($data['prompt']);
    }

    // A "Link" object.
    elseif ($obj instanceof Link || self::checkKeys($data, 'href', 'rel')) {
      $understood = TRUE;
      $data += array('name' => '', 'render' => 'link', 'prompt' => '');
      $obj = new Link($data['href'], $data['rel'], $data['name'], $data['render'], $data['prompt']);
    }

    // An "Item" object
    elseif ($obj instanceof Item || self::checkKeys($data, 'data', 'href')) {
      $understood = TRUE;
      $data += array('links' => array());
      $dataArray  = array();
      $linksArray = array();

      $obj = isset($obj) ? $obj : new Item(NULL);
      $obj->setHref($data['href']);      

      foreach ($data['data'] as $value) {
        $value       = json_encode($value);

        // We sent an empty Data object here so that we remove any chance
        // of misinterpretation of the json due to missing optional keys.
        // We do this because we have greater, contextual knowledge at this
        // point than when it tries to import just the JSON.
        $obj->addData(Object::import($value, new Data(NULL, NULL)));
      }

      foreach ($data['links'] as $value) {
        $value       = json_encode($value);
        $obj->addLink(Object::import($value, new Link(NULL, NULL)));
      }
    }

    // A "Template" object
    elseif ($obj instanceof Template || self::checkKeys($data, 'template')) {
      $understood = TRUE;
      $data       = (array) $data['template'];
      $data += array('data' => array());
      $obj = isset($obj) ? $obj : new Template;

      foreach ($data['data'] as $value) {
        $value       = json_encode($value);
        $obj->addData(Object::import($value, new Data(NULL, NULL)));
      }
    }

    // An "Error" object.
    elseif ($obj instanceof Error || self::checkKeys($data, 'code')) {
      $understood = TRUE;
      $data += array('title' => '', 'message' => '',);

      $obj = isset($obj) ? $obj : new Error('');
      $obj->setCode($data['code']);
      $obj->setTitle($data['title']);
      $obj->setMessage($data['message']);
    }    

    if (!$understood) {
      throw new \InvalidArgumentException("Unable to understand the import source.");
    }

    return $obj;
  }
}