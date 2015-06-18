<?php
namespace AKlump\Http\CollectionJson;

/**
 * Represents an error.
 */
class Error extends Object {
  
  public function __construct($code, $title = '', $message = '') {
    parent::__construct();
    if (empty($title)) {
      $title = 'Error';
    }
    if (empty($message)) {
      $message = 'An error has occurred.';
    }
    $this->setTitle($title);
    $this->setCode($code);
    $this->setMessage($message);
  }
  
  /**
   * Set the message.
   *
   * @param string $message
   *
   * @return $this
   */
  public function setMessage($message) {
    $this->data['message'] = (string) $message;
  
    return $this;
  }
  
  /**
   * Return the message.
   *
   * @return string
   */
  public function getMessage() {
    return $this->data['message'];
  }
  
  /**
   * Set the code.
   *
   * @param string $code
   *
   * @return $this
   */  
  public function setCode($code) {
    $this->data['code'] = (string) $code;
  
    return $this;
  }

  /**
   * Return the code.
   *
   * @return string
   */  
  public function getCode() {
    return $this->data['code'];
  }
  
  /**
   * Set the title.
   *
   * @param string $title
   *
   * @return $this
   */
  public function setTitle($title) {
    $this->data['title'] = (string) $title;
  
    return $this;
  }
  
  /**
   * Return the title.
   *
   * @return string
   */  
  public function getTitle() {
    return $this->data['title'];
  }

  public function asStdClass() {
    $obj = (object) array(
      'title' => $this->getTitle(), 
      'code' => $this->getCode(), 
      'message' => $this->getMessage(), 
    );

    return $obj;
  }
}