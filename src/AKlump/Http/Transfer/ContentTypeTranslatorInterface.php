<?php
namespace AKlump\Http\Transfer;

/**
 * Represents the interface for Collection.
 */
interface ContentTypeTranslatorInterface {

  /**
   * Translate from one Payload object to another
   *
   * @param  PayloadInterface $payload                
   *
   * @return PayloadInterface
   *   A new payload object in the new content type.  If the translation
   *   was not possible the object will have a mime type of 'text/error'
   *   and a message with more info.
   *   If the object's mime type is already as it should be a cloned
   *   object will be returned, not the original.
   */
  public static function translate(PayloadInterface $payload);
}