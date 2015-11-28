<?php
namespace AKlump\Http\CollectionJson;
use \AKlump\Http\Transfer\ContentTypeTranslator;
use \AKlump\Http\Transfer\PayloadInterface;
use \AKlump\Http\Transfer\Payload;

/**
 * Represents a ContentTypeTranslator for "application/vnd.collection+json" to "application/json"
 */
class CollectionJsonToJson extends ContentTypeTranslator {

  public static function translate(PayloadInterface $payload) {

    if ($payload->getContentType() === 'application/json') {
      return clone $payload;
    }

    $obj = new Payload('application/json');
    if (!in_array($payload->getContentType(), array(
      'application/vnd.collection+json'
    ))) {
      return static::failedTranslation($payload);
    }
    
    $source = json_decode($payload->getContent());
    $items = array();
    $output = new \stdClass;

    $root = 'collection';
    if (isset($source->collection->items)) {
      $items = $source->collection->items;
    }
    elseif (isset($source->template->data)) {
      $items = array((object) array('data' => $source->template->data));
      $root = 'template';
    }

    foreach ($items as $item) {
      $output_item = (object) array('data' => new \stdClass);
      if (isset($item->data)) {
        foreach ($item->data as $data) {
          if (!empty($data->name)) {
            $output_item->data->{$data->name} = $data->value;
          }
        }
      }
      if (isset($item->links)) {
        foreach ($item->links as $link) {
          if (!empty($link->name)) {
            $render = isset($link->render) ? $link->render : 'link';
            $key = $render . 's';
            if (!isset($output_item->{$key})) {
              $output_item->{$key} = new \stdClass;
            }
            $output_item->{$key}->{$link->name} = $link->href;
          }
        }
      }
      $output->items[] = $output_item;
    }

    if (isset($source->collection->error)) {
      $output->error = $source->collection->error;
    }

    switch ($root) {
      case 'collection':
        $output = (object) array('collection' => $output);
        break;
      
      case 'template':
        $output = (object) array('template' => reset($output->items));
        break;
    }

    $obj->setContent(json_encode($output));

    return $obj;
  }  
}