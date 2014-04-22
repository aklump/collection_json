<?php
namespace AKlump\Http\Transfer;

/**
 * Represents a ContentTypeTranslator.
 */
class Payload implements PayloadInterface {
  
  protected $data = array(
    'contentType' => '',
    'content' => '',
  );

  public function __construct($mimeType, $content = '') {
    $this->setContentType($mimeType);
    $this->setContent($content);
  }
  
  public function setContentType($mimeType) {
    $this->data['contentType'] = (string) $mimeType;
  
    return $this;
  }
  
  public function getContentType() {
    return $this->data['contentType'];
  }  

  public function setContent($content) {
    $this->data['content'] = (string) $content;
  
    return $this;
  }
  
  public function getContent() {
    return $this->data['content'];
  }  

  public function __toString() {
    return $this->getContent();
  }
}