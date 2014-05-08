<?php
namespace AKlump\Http\CollectionJson;
use \AKlump\Http\Transfer\ContentTypeTranslaterInterface;
use \AKlump\Http\Transfer\PayloadInterface;
use \AKlump\Http\Transfer\Payload;

/**
 * Represents a ContentTypeTranslator for "application/vnd.collection+json" to "application/json"
 */
class CollectionJsonToJson implements ContentTypeTranslaterInterface {

  public function translate(PayloadInterface $payload) {

    if ($payload->getContentType() === 'application/json') {
      return clone $payload;
    }

    $obj = new Payload('application/json');
    if (!in_array($payload->getContentType(), array(
      'application/vnd.collection+json'
    ))) {
      $obj->setContent('Bad content type: ' . $payload->getContentType());
      return $obj;
    }
    

    $source = json_decode($payload->getContent());
    $items = $output = array();

    if (isset($source->collection->items)) {
      $items = $source->collection->items;
    }
    elseif (isset($source->template->data)) {
      $items = array((object) array('data' => $source->template->data));
    }

    foreach ($items as $item) {
      $output_item = new \stdClass;
      foreach ($item->data as $data) {
        $output_item->{$data->name} = $data->value;
      }
      $output[] = $output_item;
    }


    if (isset($source->collection->error)) {
      $output[] = (object) array('error' => $source->collection->error);
    }

    // Reduce a single set to one object
    if (count($output) === 1) {
      $output = reset($output);
    }

    // Make an empty value an object
    if (empty($output)) {
      $output = (object) $output;
    }

    $obj->setContent(json_encode($output));

    return $obj;
  }  
}