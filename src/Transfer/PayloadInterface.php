<?php
namespace AKlump\Http\Transfer;

/**
 * Represents the interface for a payload object.
 */
interface PayloadInterface {
  /**
   * Set the contentType.
   *
   * @param string $mimeType
   *
   * @return $this
   */
  public function setContentType($mimeType);
  
  /**
   * Return the contentType.
   *
   * @return string
   */
  public function getContentType();
  
  /**
   * Set the content.
   *
   * @param string $content
   *
   * @return $this
   */
  public function setContent($content);
  
  /**
   * Return the content.
   *
   * @return string
   */
  public function getContent();  
}