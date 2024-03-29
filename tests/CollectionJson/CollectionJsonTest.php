<?php
/**
 * @file
 * PHPUnit tests for the CollectionJsonTest class
 *
 */

namespace AKlump\Http\CollectionJson;

use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\Http\CollectionJson\Collection
 */
final class CollectionJsonTest extends TestCase {

  public function testExampleCode() {
    $col = new Collection('http://www.intheloftstudios.com/api/1.0/packages');

    // Add an item to the collection.
    $col->addItem(new Item('http://www.intheloftstudios.com/api/1.0/packages', array(
      new Data('title', 'CollectionJson', 'Title'),
      new Data('author', 'Aaron Klump', 'Author'),
    ), array(
      new Link('http://www.intheloftstudios.com/collection-json', 'item'),
    )));

    // Add the template.
    $col->setTemplate(new Template(array(
      new Data('title', '', 'Title'),
      new Data('author', '', 'Author'),
    )));

    $json = strval($col);
    $this->assertSame('{"collection":{"version":"1.0","href":"http:\/\/www.intheloftstudios.com\/api\/1.0\/packages","items":[{"href":"http:\/\/www.intheloftstudios.com\/api\/1.0\/packages","data":[{"name":"title","prompt":"Title","value":"CollectionJson"},{"name":"author","prompt":"Author","value":"Aaron Klump"}],"links":[{"href":"http:\/\/www.intheloftstudios.com\/collection-json","rel":"item","render":"link"}]}],"template":{"data":[{"name":"title","prompt":"Title","value":""},{"name":"author","prompt":"Author","value":""}]}}}', $json);
  }

  public function testHasGetLinkByName() {
    $obj = new Collection;
    $obj->setLinks(array(
      new Link('http://www.google.com', 'alternate', 'google'),
      new Link('http://www.yahoo.com', 'alternate', 'yahoo'),
    ));
    $this->assertTrue($obj->hasLinkByName('google'));
    $this->assertTrue($obj->hasLinkByName('yahoo'));
    $this->assertFalse($obj->hasLinkByName('bing'));

    $this->assertSame('http://www.google.com', $obj->getLinkByName('google')
      ->getHref());
    $this->assertSame('http://www.yahoo.com', $obj->getLinkByName('yahoo')
      ->getHref());
    $this->assertSame('', $obj->getLinkByName('bing')->getHref());
  }

  public function testCollectionError() {
    $obj = new Collection();
    $obj->setError(new Error(404, 'Not Found', 'Resource not found.'));
    $control = '{"collection":{"version":"1.0","href":"","error":{"title":"Not Found","code":"404","message":"Resource not found."}}}';
    $this->assertSame($control, (string) $obj);

    $obj = new Collection();
    $obj->setContent($control);
    $this->assertSame($control, (string) $obj);
  }

  public function testCollectionSetContent() {
    $obj = new Collection();
    $subject = <<<EOD
{"collection":{"version":"1.0","href":"http:\/\/www.ovagraph.com\/api\/3\/cycles","links":[{"href":"http:\/\/www.ovagraph.com\/cycles\/1","rel":"alternate","name":"ovagraph_cycles_table","render":"link","prompt":"View on OvaGraph.com"}],"items":[{"href":"http:\/\/www.ovagraph.com\/api\/3\/cycles\/589161","data":[{"name":"title","prompt":"Title","value":"Cycle #13 (Starting 1\/7\/14)"},{"name":"ce","prompt":"Cycle end date","value":""},{"name":"cn","prompt":"Cycle number","value":13},{"name":"cl","prompt":"Cycle length","value":0},{"name":"cs","prompt":"Cycle start date","value":"2014-01-07"},{"name":"ic","prompt":"Cycle UUID","value":589161},{"name":"ms","prompt":"Fertility meds start day","value":5},{"name":"ul","prompt":"Average Cycle Length (in OvaCue)","value":31},{"name":"uo","prompt":"\"My\" Revised Ovulation Day","value":17},{"name":"xa","prompt":"Exclude from the All Cycles Average?","value":0},{"name":"cn","prompt":"Notes","value":"This was the cycle where we went to Disneyland."},{"name":"days(1)","prompt":"Days","value":[689582,689583,689584,689585,689586,689587,689588,689589,689590]},{"name":"days(2)","prompt":"Days","value":[{"bf":96,"d":"2014-01-07","id":689582,"or":200,"ot":"07:31-08:00","vr":100},{"bf":99,"d":"2014-01-08","id":689583,"or":210,"ot":"07:313-08:00","vr":160},{"bf":95,"d":"2014-01-09","id":689584,"or":190,"ot":"07:31-08:00","vr":140},{"bf":100,"d":"2014-01-10","id":689585,"or":200,"ot":"07:32-08:00","vr":150},{"d":"2014-01-11","id":689586,"or":200,"ot":"07:31-08:00","vr":55},{"d":"2014-01-12","id":689587,"or":200,"ot":"07:36-08:00","vr":21},{"d":"2014-01-13","id":689588,"or":100,"ot":"07:31-08:00"},{"d":"2014-01-14","id":689589,"vr":397},{"d":"2014-01-15","id":689590,"vr":1}]}],"links":[{"href":"http:\/\/www.ovagraph.com\/records\/1\/cycles\/589161","rel":"alternate","name":"ovagraph_node_view","render":"link","prompt":"View on OvaGraph.com"},{"href":"http:\/\/www.ovagraph.com\/cycles\/1\/589161\/chart","rel":"alternate","name":"ovagraph_cycle_chart","render":"link","prompt":"View chart"},{"href":"http:\/\/www.ovagraph.com\/cycles\/1\/589161\/calendar","rel":"alternate","name":"ovagraph_cycle_calendar","render":"link","prompt":"View calendar"},{"href":"http:\/\/www.ovagraph.com\/cycles\/1\/589161\/days","rel":"alternate","name":"ovagraph_cycle_table","render":"link","prompt":"View table"},{"href":"http:\/\/www.ovagraph.com\/sites\/ovagraph.com\/files\/tickers\/1\/ovagraph-ticker-small.png","rel":"icon","name":"small_ticker","render":"image","prompt":"Small ticker image for Cycle #13 (Starting 1\/7\/14)"},{"href":"http:\/\/www.ovagraph.com\/sites\/ovagraph.com\/files\/tickers\/1\/ovagraph-ticker-large.png","rel":"icon","name":"large_ticker","render":"image","prompt":"Large ticker image for Cycle #13 (Starting 1\/7\/14)"}]}],"queries":[{"href":"http:\/\/www.ovagraph.com\/api\/3\/cycles","rel":"subsection","prompt":"Alter the results","data":[{"name":"max","prompt":"Max cycles","value":5},{"name":"order_by","prompt":"Sort order (desc, asc)","value":"desc"},{"name":"cn","prompt":"By cycle number","value":1},{"name":"cs_from","prompt":"Minimum start date","value":""},{"name":"cs_to","prompt":"Maximum start date","value":""},{"name":"days","prompt":"Include day objects (0=none, 1=ids only, 2=full objects)","value":0},{"name":"html","prompt":"Type of html markup (0=none, 1=adaptive or fixed, 2=fixed only)?","value":1},{"name":"view","prompt":"For which view to build html? (calendar, days, chart)?","value":"chart"}]}],"template":{"data":[{"name":"cs","prompt":"Cycle start date","value":"2014-01-07"},{"name":"ms","prompt":"Fertility meds start day","value":5},{"name":"ul","prompt":"Average Cycle Length (in OvaCue)","value":31},{"name":"uo","prompt":"\"My\" Revised Ovulation Day","value":17},{"name":"xa","prompt":"Exclude from the All Cycles Average?","value":0},{"name":"cn","prompt":"Notes","value":"This was the cycle where we went to Disneyland."},{"name":"days","prompt":"Days","value":[{"bf":96,"d":"2014-01-07","id":689582,"or":200,"ot":"07:31-08:00","vr":100},{"bf":99,"d":"2014-01-08","or":210,"ot":"07:313-08:00","vr":160},{"bf":95,"d":"2014-01-09","or":190,"ot":"07:31-08:00","vr":140},{"bf":100,"d":"2014-01-10","or":200,"ot":"07:32-08:00","vr":150},{"d":"2014-01-11","or":200,"ot":"07:31-08:00","vr":55},{"d":"2014-01-12","or":200,"ot":"07:36-08:00","vr":21},{"d":"2014-01-13","or":100,"ot":"07:31-08:00"},{"d":"2014-01-14","vr":397},{"d":"2014-01-15","vr":1}]}]}}}
EOD;
    $return = $obj->setContent($subject);
    $this->assertInstanceOf('\AKlump\Http\CollectionJson\Collection', $return);

    $items = $obj->getItems();
    $this->assertCount(1, $items);

    $links = $obj->getLinks();
    $this->assertCount(1, $links);

    $this->assertCount(7, $obj->getTemplate()->getDataArray());

    $this->assertSame($subject, (string) $obj);
  }


  public function testTemplateSetContentUsingCollectionExtracts() {
    $obj = new Template();
    $subject = <<<EOD
{
    "collection": {
        "href": "http://www.ovagraph.com/api/3/users/123",
        "items": [
            {
                "data": [
                    {
                        "name": "uid",
                        "value": "123"
                    },
                    {
                        "name": "user",
                        "value": "lsmith"
                    },
                    {
                        "name": "mail",
                        "value": "ssmith@gmail.com"
                    },
                    {
                        "name": "first",
                        "value": "Linda"
                    },
                    {
                        "name": "last",
                        "value": "Smith"
                    },
                    {
                        "name": "timezone",
                        "value": "Pacific/Tahiti"
                    },
                    {
                        "name": "created",
                        "value": "1393010987"
                    },
                    {
                        "name": "temp_units",
                        "value": "f"
                    },
                    {
                        "name": "cl",
                        "value": 28
                    },
                    {
                        "name": "devices",
                        "value": [
                            {
                                "id": "02FFFF00",
                                "name": "Fairhaven Health Mobile Adapter",
                                "type": 2
                            }
                        ]
                    }
                ],
                "href": "http://www.ovagraph.com/api/3/users/123",
                "links": [
                    {
                        "href": "http://www.ovagraph.com/user/123",
                        "name": "about",
                        "prompt": "More info",
                        "rel": "about",
                        "render": "link"
                    }
                ]
            }
        ],
        "template": {
            "data": [
                {
                    "name": "user",
                    "prompt": "Username",
                    "value": "lsmith"
                },
                {
                    "name": "pass",
                    "prompt": "Password",
                    "value": "secret"
                },
                {
                    "name": "mail",
                    "prompt": "E-mail address",
                    "value": "ssmith@gmail.com"
                },
                {
                    "name": "first",
                    "prompt": "First Name",
                    "value": "Linda"
                },
                {
                    "name": "last",
                    "prompt": "Last Name",
                    "value": "Smith"
                },
                {
                    "name": "devices",
                    "prompt": "Devices",
                    "value": [
                        {
                            "id": "02FFFF00",
                            "type": 2
                        }
                    ]
                },
                {
                    "name": "timezone",
                    "prompt": "Time zone",
                    "value": "Pacific/Tahiti"
                },
                {
                    "name": "temp_units",
                    "prompt": "Preferred Temperature Units",
                    "value": "f"
                },
                {
                    "name": "cl",
                    "prompt": "Average Cycle Length (in OvaCue)",
                    "value": "28"
                }
            ]
        },
        "version": "1.0"
    }
}
EOD;
    $return = $obj->setContent($subject);
    $this->assertInstanceOf('\AKlump\Http\CollectionJson\Template', $return);
    $dataArray = $obj->getDataArray();
    $this->assertCount(9, $dataArray);

    $this->assertSame('application/vnd.collection+json', $obj->getContentType());
  }

  public function testTemplateSetContentInvalidJson() {
    $obj = new Template();
    $subject = <<<EOD
{
        "data": [
            {
                "name": "last",
                "value": "Smith",
                "prompt": "Last name"
            },
            {
                "name": "devices",
                "value": [
                    {
                        "id": "02FFFF00",
                        "type": 2
                    }
                ]
            }
        ]
}    
EOD;
    $return = $obj->setContent($subject);
    $this->assertInstanceOf('\AKlump\Http\CollectionJson\Template', $return);
    $dataArray = $obj->getDataArray();
    $this->assertCount(0, $dataArray);
  }

  public function testTemplateSetContent() {
    $obj = new Template();
    $subject = <<<EOD
{
    "template": {
        "data": [
            {
                "name": "last",
                "value": "Smith",
                "prompt": "Last name"
            },
            {
                "name": "devices",
                "value": [
                    {
                        "id": "02FFFF00",
                        "type": 2
                    }
                ]
            }
        ]
    }
}    
EOD;
    $return = $obj->setContent($subject);
    $this->assertInstanceOf('\AKlump\Http\CollectionJson\Template', $return);

    $dataArray = $obj->getDataArray();
    $this->assertCount(2, $dataArray);
    $this->assertInstanceOf('\AKlump\Http\CollectionJson\Data', $dataArray[0]);
    $this->assertSame('last', $dataArray[0]->getName());
    $this->assertSame('Smith', $dataArray[0]->getValue());
    $this->assertSame('Last name', $dataArray[0]->getPrompt());

    $this->assertSame('devices', $dataArray[1]->getName());
    $control = array((object) array('id' => '02FFFF00', 'type' => 2));
    $this->assertEquals($control, $dataArray[1]->getValue());

    $this->assertSame('{"template":{"data":[{"name":"last","prompt":"Last name","value":"Smith"},{"name":"devices","value":[{"id":"02FFFF00","type":2}]}]}}', (string) $obj);
  }

  public function testError() {
    $obj = new Error(404);
    $control = '{"title":"Error","code":"404","message":"An error has occurred."}';
    $this->assertSame($control, (string) $obj);

    $obj = new Error(403, 'Forbidden', 'You do not have access to that.');
    $control = '{"title":"Forbidden","code":"403","message":"You do not have access to that."}';
    $this->assertSame($control, (string) $obj);
  }

  public function testTemplate() {
    $obj = new Template(array(
      new Data('first', '', 'First Name'),
      new Data('last', '', 'Last Name'),
      new Data('age', '', 'Age'),
      new Data('color', ''),
    ));
    $control = '{"template":{"data":[{"name":"first","prompt":"First Name","value":""},{"name":"last","prompt":"Last Name","value":""},{"name":"age","prompt":"Age","value":""},{"name":"color","value":""}]}}';
    $this->assertSame($control, (string) $obj);
  }

  public function testQuery() {
    $obj = new Query('http://example.org/search', array(
      new Data('search', ''),
    ));
    $control = '{"href":"http:\/\/example.org\/search","rel":"","data":[{"name":"search","value":""}]}';
    $this->assertSame($control, (string) $obj);

    $obj = new Query('http://example.org/search', array(
      new Data('search', ''),
    ), 'search', 'Enter search string');
    $control = '{"href":"http:\/\/example.org\/search","rel":"search","prompt":"Enter search string","data":[{"name":"search","value":""}]}';
    $this->assertSame($control, (string) $obj);
  }

  public function testCollection() {
    $obj = new Collection('http://www.website.com/api/1.0/item/1');
    $control = '{"collection":{"version":"1.0","href":"http:\/\/www.website.com\/api\/1.0\/item\/1"}}';
    $this->assertSame($control, (string) $obj);

    $obj->addLink(new Link('http://www.website.com/api/1.0/item/1', 'alternate', 'view', 'link', 'View on website'));
    $obj->addLink(new Link('http://alt.website.com/api/1.0/item/17', 'alternate', 'view', 'link', 'View on website2'));
    $control = '{"collection":{"version":"1.0","href":"http:\/\/www.website.com\/api\/1.0\/item\/1","links":[{"href":"http:\/\/www.website.com\/api\/1.0\/item\/1","rel":"alternate","name":"view","render":"link","prompt":"View on website"},{"href":"http:\/\/alt.website.com\/api\/1.0\/item\/17","rel":"alternate","name":"view","render":"link","prompt":"View on website2"}]}}';
    $this->assertSame($control, (string) $obj);

    $obj->addItem(new Item('http://www.website.com/api/1.0/person/1', array(
      new Data('first', 'Clark', 'First Name'),
      new Data('last', 'Kent', 'Last Name'),
      new Data('age', 39, 'Age'),
      new Data('color', 'black'),
    )));
    $obj->addItem(new Item('http://www.website.com/api/1.0/person/2', array(
      new Data('first', 'Louis', 'First Name'),
      new Data('last', 'Lane', 'Last Name'),
      new Data('age', 36, 'Age'),
      new Data('color', 'brown'),
    )));
    $control = '{"collection":{"version":"1.0","href":"http:\/\/www.website.com\/api\/1.0\/item\/1","links":[{"href":"http:\/\/www.website.com\/api\/1.0\/item\/1","rel":"alternate","name":"view","render":"link","prompt":"View on website"},{"href":"http:\/\/alt.website.com\/api\/1.0\/item\/17","rel":"alternate","name":"view","render":"link","prompt":"View on website2"}],"items":[{"href":"http:\/\/www.website.com\/api\/1.0\/person\/1","data":[{"name":"first","prompt":"First Name","value":"Clark"},{"name":"last","prompt":"Last Name","value":"Kent"},{"name":"age","prompt":"Age","value":39},{"name":"color","value":"black"}]},{"href":"http:\/\/www.website.com\/api\/1.0\/person\/2","data":[{"name":"first","prompt":"First Name","value":"Louis"},{"name":"last","prompt":"Last Name","value":"Lane"},{"name":"age","prompt":"Age","value":36},{"name":"color","value":"brown"}]}]}}';
    $this->assertSame($control, (string) $obj);


    $obj->setQueries(array(
      new Query('http://example.org/search', array(new Data('search', '')), 'search', 'Enter search string'),
      new Query('http://example.org/find', array(new Data('find', '')), 'find', 'Enter find string'),
    ));
    $control = '{"collection":{"version":"1.0","href":"http:\/\/www.website.com\/api\/1.0\/item\/1","links":[{"href":"http:\/\/www.website.com\/api\/1.0\/item\/1","rel":"alternate","name":"view","render":"link","prompt":"View on website"},{"href":"http:\/\/alt.website.com\/api\/1.0\/item\/17","rel":"alternate","name":"view","render":"link","prompt":"View on website2"}],"items":[{"href":"http:\/\/www.website.com\/api\/1.0\/person\/1","data":[{"name":"first","prompt":"First Name","value":"Clark"},{"name":"last","prompt":"Last Name","value":"Kent"},{"name":"age","prompt":"Age","value":39},{"name":"color","value":"black"}]},{"href":"http:\/\/www.website.com\/api\/1.0\/person\/2","data":[{"name":"first","prompt":"First Name","value":"Louis"},{"name":"last","prompt":"Last Name","value":"Lane"},{"name":"age","prompt":"Age","value":36},{"name":"color","value":"brown"}]}],"queries":[{"href":"http:\/\/example.org\/search","rel":"search","prompt":"Enter search string","data":[{"name":"search","value":""}]},{"href":"http:\/\/example.org\/find","rel":"find","prompt":"Enter find string","data":[{"name":"find","value":""}]}]}}';
    $this->assertSame($control, (string) $obj);

    $obj->setTemplate(new Template(array(
      new Data('first', '', 'First Name'),
      new Data('last', '', 'Last Name'),
      new Data('age', '', 'Age'),
      new Data('color', ''),
    )));

    $control = '{"collection":{"version":"1.0","href":"http:\/\/www.website.com\/api\/1.0\/item\/1","links":[{"href":"http:\/\/www.website.com\/api\/1.0\/item\/1","rel":"alternate","name":"view","render":"link","prompt":"View on website"},{"href":"http:\/\/alt.website.com\/api\/1.0\/item\/17","rel":"alternate","name":"view","render":"link","prompt":"View on website2"}],"items":[{"href":"http:\/\/www.website.com\/api\/1.0\/person\/1","data":[{"name":"first","prompt":"First Name","value":"Clark"},{"name":"last","prompt":"Last Name","value":"Kent"},{"name":"age","prompt":"Age","value":39},{"name":"color","value":"black"}]},{"href":"http:\/\/www.website.com\/api\/1.0\/person\/2","data":[{"name":"first","prompt":"First Name","value":"Louis"},{"name":"last","prompt":"Last Name","value":"Lane"},{"name":"age","prompt":"Age","value":36},{"name":"color","value":"brown"}]}],"queries":[{"href":"http:\/\/example.org\/search","rel":"search","prompt":"Enter search string","data":[{"name":"search","value":""}]},{"href":"http:\/\/example.org\/find","rel":"find","prompt":"Enter find string","data":[{"name":"find","value":""}]}],"template":{"data":[{"name":"first","prompt":"First Name","value":""},{"name":"last","prompt":"Last Name","value":""},{"name":"age","prompt":"Age","value":""},{"name":"color","value":""}]}}}';
    $this->assertSame($control, (string) $obj);
  }

  public function testData() {
    $obj = new Data('hair_color', 'blonde');
    $control = '{"name":"hair_color","value":"blonde"}';
    $this->assertSame($control, (string) $obj);

    $obj = new Data('hair_color', 'blonde', 'Hair');
    $control = '{"name":"hair_color","prompt":"Hair","value":"blonde"}';
    $this->assertSame($control, (string) $obj);
  }

  public function testLink() {
    $obj = new Link('http://www.website.com/api/1.0/item/1', 'alternate', 'website');
    $control = '{"href":"http:\/\/www.website.com\/api\/1.0\/item\/1","rel":"alternate","name":"website","render":"link"}';
    $this->assertSame($control, (string) $obj);

    $obj = new Link('http://www.website.com/api/1.0/item/1', 'alternate', 'view', 'link', 'View on website');
    $control = '{"href":"http:\/\/www.website.com\/api\/1.0\/item\/1","rel":"alternate","name":"view","render":"link","prompt":"View on website"}';
    $this->assertSame($control, (string) $obj);
  }

  public function testItem() {
    $item = new Item('http://www.website.com/api/1.0/item/1');
    $control = <<<EOD
{"href":"http:\/\/www.website.com\/api\/1.0\/item\/1"}
EOD;
    $this->assertSame($control, (string) $item);

    $item = new Item('http://www.website.com/api/1.0/item/1', array(
      new Data('first', 'Clark', 'First Name'),
      new Data('last', '', 'Last Name'),
      new Data('age', 39, 'Age'),
      new Data('color', 'black'),
    ));
    $control = <<<EOD
{"href":"http:\/\/www.website.com\/api\/1.0\/item\/1","data":[{"name":"first","prompt":"First Name","value":"Clark"},{"name":"last","prompt":"Last Name","value":""},{"name":"age","prompt":"Age","value":39},{"name":"color","value":"black"}]}
EOD;
    $this->assertSame($control, (string) $item);

    $item = new Item('http://www.website.com/api/1.0/item/1', array(
      new Data('first', 'Clark', 'First Name'),
      new Data('last', '', 'Last Name'),
      new Data('age', 39, 'Age'),
      new Data('color', 'black'),
    ), array(
      new Link('http://www.website.com/node/1', 'alternate', 'node'),
      new Link('http://www.website.com/image.jpg', 'photo', 'photo', 'image'),
    ));
    $control = <<<EOD
{"href":"http:\/\/www.website.com\/api\/1.0\/item\/1","data":[{"name":"first","prompt":"First Name","value":"Clark"},{"name":"last","prompt":"Last Name","value":""},{"name":"age","prompt":"Age","value":39},{"name":"color","value":"black"}],"links":[{"href":"http:\/\/www.website.com\/node\/1","rel":"alternate","name":"node","render":"link"},{"href":"http:\/\/www.website.com\/image.jpg","rel":"photo","name":"photo","render":"image"}]}
EOD;
    $this->assertSame($control, (string) $item);
  }


}
