<?php
namespace AKlump\LoftLib\Api\CollectionJson;
use \AKlump\LoftLib\Api\PayloadInterface;

/**
 * Represents a Collection.
 *
 * This is a standalone object, as is Template.
 */
class Collection extends Object implements PayloadInterface {

  public function __construct($href) {
    $this->setHref($href);
    $this->data += array(
      'version' => '1.0'
    );
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

    return (object) array('collection' => $obj);
  }

  public function setContentType($mimeType) {
    return $this;
  }
  
  public function getContentType() {
    return 'application/vnd.collection+json';
  }  

  public function setContent($content) {
    $json = (string) $content;
  
    //@todo do this thing







    return $this;
  }
  
  public function getContent() {
    return $this->__toString();
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
}