<?php
/**
 * @file
 * Defines the ContentTypeTranslator class.
 *
 * @ingroup name
 * @{
 */
namespace AKlump\Http\Transfer;

/**
 * Represents a ContentTypeTranslator object class.
 * 
 * @brief Base class for translators
 */
abstract class ContentTypeTranslator implements ContentTypeTranslatorInterface {
  
  /**
   * Method to produce and return a consistent Payload object.
   *
   * Use this when a translation fails.
   *
   * @param  \AKlump\Http\Transfer\Payload $payload [description]
   *
   * @return \AKlump\Http\Transfer\Payload A new payload containing a failure message.
   */
  public static function failedTranslation($payload) {
    $obj = new Payload('text/error');
    $obj->setContent('Unable to recognize/translate mime type: ' . $payload->getContentType());
    
    return $obj;    
  }
  
}
