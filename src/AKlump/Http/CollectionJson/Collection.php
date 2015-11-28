<?php
namespace AKlump\Http\CollectionJson;
use \AKlump\Http\Transfer\PayloadInterface;

/**
 * Represents a Collection.
 *
 * This is a standalone object, as is Template.
 *
 * Here is an example of how you would quickly build a collection and print
 * out the JSON.
 * @code
 *  <?php
 *   $col = new Collection('http://www.intheloftstudios.com/api/1.0/packages'); 
 * 
 *   // Add an item to the collection.
 *   $col->addItem(new Item('http://www.intheloftstudios.com/api/1.0/packages', array(
 *     new Data('title', 'CollectionJson', 'Title'),
 *     new Data('author', 'Aaron Klump', 'Author'),
 *   ), array(
 *     new Link('http://www.intheloftstudios.com/collection-json', 'item'),
 *   )));
 * 
 *   // Add the template.
 *   $col->setTemplate(new Template(array(
 *     new Data('title', '', 'Title'),
 *     new Data('author', '', 'Author'),
 *   )));
 * 
 *   $json = strval($col);
 * @endcode
 *
 * @see  Object::Import
 */
class Collection extends Object implements PayloadInterface {

  protected $template, $error;

  public function __construct($href = '') {
    parent::__construct();
    $this->setHref($href);
    if (empty($this->data['version'])) {
      $this->data['version'] = "1.0";
    }
    if (empty($this->data['items'])) {
      $this->data['items'] = array();
    }
    if (empty($this->data['queries'])) {
      $this->data['queries'] = array();
    }
  }

  protected function asStdClass() {
    $obj = new \stdClass;
    $obj->version = $this->getVersion();
    $obj->href = $this->getHref();

    foreach($this->getLinks() as $l) {
      $obj->links[] = $l->asStdClass();
    }

    foreach($this->getItems() as $item) {
      $obj->items[] = $item->asStdClass();
    }

    foreach($this->getQueries() as $query) {
      $obj->queries[] = $query->asStdClass();
    }

    if ($t = $this->getTemplate()) {
      $obj->template = $t->asStdClass()->template;
    }

    if ($e = $this->getError()) {
      $obj->error = $e->asStdClass();
    }

    return (object) array('collection' => $obj);
  }

  public function setContentType($mimeType) {
    return $this;
  }
  
  public function getContentType() {
    return 'application/vnd.collection+json';
  }  

  /**
   * Sets the content of the Collection from a JSON string.
   *
   * @param string $content JSON.
   */
  public function setContent($content) {
    $obj = Object::import($content);

    $this->setVersion($obj->getVersion());
    $this->setHref($obj->getHref());
    $this->setLinks($obj->getLinks());
    $this->setItems($obj->getItems());
    $this->setQueries($obj->getQueries());
    if ($t = $obj->getTemplate()) {
      $this->setTemplate($t);
    }
    if ($e = $obj->getError()) {
      $this->setError($e);
    }
    
    return $this;
  }
  
  public function getContent() {
    return $this->__toString();
  }

  /**
   * Set the Template object.
   *
   * @param Template $template
   *
   * @return $this
   */
  public function setTemplate(Template $template) {
    $this->template = $template;
  
    return $this;
  }
  
  /**
   * Return the Template object.
   *
   * @return Template
   */  
  public function getTemplate() {
    return $this->template;
  }  

  /**
   * Set the version.
   *
   * @param string $version
   *
   * @return $this
   */
  public function setVersion($version) {
    $this->data['version'] = (string) $version;
  
    return $this;
  }

  /**
   * Return the version.
   *
   * @return string
   */  
  public function getVersion() {
    return $this->data['version'];
  }

  /**
   * Set the queries array.
   *
   * @param array $queries
   *
   * @return $this
   */  
  public function setQueries(Array $queries) {
    $this->data['queries'] = array();
    foreach($queries as $querie) {
      $this->addQuery($querie);
    }
  
    return $this;
  }

  /**
   * Adds a single query.
   *
   * @param Query $query
   *
   * @return $this
   */  
  public function addQuery(Query $query) {
    $this->data['queries'][] = $query;
  
    return $this;
  }
  
  /**
   * Return the queries array.
   *
   * @return array
   */
  public function getQueries() {
    return $this->data['queries'];
  }

  /**
   * Set the Error object.
   *
   * @param Error $error
   *
   * @return $this
   */  
  public function setError(Error $error) {
    $this->error = $error;
  
    return $this;
  }
 
  /**
   * Return the Error object.
   *
   * @return Error
   */ 
  public function getError() {
    return $this->error;
  }
}