<?php
namespace AKlump\Http\CollectionJson;
use \AKlump\Http\Transfer\PayloadInterface;

/**
 * Represents a Collection.
 *
 * This is a standalone object, as is Template.
 */
class Collection extends Object implements PayloadInterface {

  protected $template, $error;

  public function __construct($href = '') {
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

  public function setContent($content) {
    $obj = json_decode((string) $content);

    if (isset($obj->collection)) {
      $obj = $obj->collection;

      if (isset($obj->version)) {
        $this->setVersion($obj->version);
      }
          
      if (isset($obj->href)) {
        $this->setHref($obj->href);
      }

      if (isset($obj->links)) {
        $links = array();
        foreach ($obj->links as $link) {
          $render   = isset($link->render) ? $link->render : NULL;
          $name     = isset($link->name) ? $link->name : NULL;
          $prompt   = isset($link->prompt) ? $link->prompt : NULL;
          $links[]  = new Link($link->href, $link->rel, $render, $name, $prompt);
        }
        $this->setLinks($links);
      }

      if (isset($obj->items)) {
        $items = array();
        foreach ($obj->items as $item) {

          $links = array();
          if (isset($item->links)) {
            foreach ($item->links as $link) {
              $render   = isset($link->render) ? $link->render : NULL;
              $name     = isset($link->name) ? $link->name : NULL;
              $prompt   = isset($link->prompt) ? $link->prompt : NULL;
              $links[]  = new Link($link->href, $link->rel, $render, $name, $prompt);
            }          
          }
          $dataArray = array();
          if (isset($item->data)) {
            foreach ($item->data as $data) {
              $prompt = isset($data->prompt) ? $data->prompt : NULL;
              $dataArray[] = new Data($data->name, $data->value, $prompt);
            }
          }
  
          $items[]  = new Item($item->href, $dataArray, $links);
        }
        $this->setItems($items);
      }

      if (isset($obj->queries)) {
        $queries = array();
        foreach ($obj->queries as $query) {
          $dataArray = array();
          if (isset($query->data)) {
            foreach ($query->data as $data) {
              $prompt = isset($data->prompt) ? $data->prompt : NULL;
              $dataArray[] = new Data($data->name, $data->value, $prompt);
            }
          }
          $rel       = isset($query->rel) ? $query->rel : NULL;
          $prompt    = isset($query->prompt) ? $query->prompt : NULL;
          $queries[] = new Query($query->href, $dataArray, $rel, $prompt);
        }
        $this->setQueries($queries);
      }

      if (isset($obj->template)) {
        $t = new Template();
        $t->setContent(json_encode($obj));
        $this->setTemplate($t);
      }

      if (isset($obj->error)) {
        $code     = isset($obj->error->code) ? $obj->error->code : '';
        $title    = isset($obj->error->title) ? $obj->error->title : '';
        $message  = isset($obj->error->message) ? $obj->error->message : '';
        $this->setError(new Error($code, $title, $message));
      }
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
  public function setQueries($queries) {
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