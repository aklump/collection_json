<?php
namespace AKlump\Http\Transfer;

/**
 * Represents the interface for Collection.
 */
interface ContentTypeTranslaterInterface {

  /**
   * Translate from one Payload object to another
   *
   * @param  PayloadInterface $payload                
   *
   * @return FALSE|PayloadInterface
   *   A new payload object in the new content type or false on failure.
   */
  public static function translate(PayloadInterface $payload);
}