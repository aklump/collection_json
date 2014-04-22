<?php
/**
 * @file
 * PHPUnit tests for the CollectionJsonToJson class
 *
 */

namespace AKlump\Http\CollectionJson;
use AKlump\Http\Transfer\Payload;

require_once dirname(__FILE__) . '/../vendor/autoload.php';

class CollectionJsonToJsonTest extends \PHPUnit_Framework_TestCase {

  public function testError() {
    $subject = <<<EOD
{"collection":{"version":"1.0","href":"http:\/\/dev.ovagraph.local\/api\/3.0\/cycles","error":{"title":"Not Acceptable","code":"406","message":"Invalid query provided, double check that the fields and parameters you defined are correct and exist."}}}    
EOD;
    $payload = new Payload('application/vnd.collection+json', $subject);
    $result = CollectionJsonToJson::translate($payload);

    $control = <<<EOD
"406 Not Acceptable: Invalid query provided, double check that the fields and parameters you defined are correct and exist."
EOD;
    $this->assertSame($control, $result->getContent());
  }

  public function testPassJsonThroughAsClonedObject() {
    $subject = '{"bf":98.6,"br":1,"bt":"05:12-08:00","cd":9,"cf":"s","cm":"w","cp":"l"}';
    $payload = new Payload('application/json', $subject);
    $result = CollectionJsonToJson::translate($payload);
    // print($result); die;
    $this->assertInstanceOf('\AKlump\Http\Transfer\PayloadInterface', $result);
    $this->assertNotSame($payload, $result);
    $this->assertSame($subject, $result->getContent());
    $this->assertSame($subject, (string) $result);    
  }

  public function testTemplate() {
    $subject = <<<EOD
{
    "template": {
        "data": [
            {
                "name": "bf",
                "value": 98.6
            },
            {
                "name": "br",
                "value": 1
            },
            {
                "name": "bt",
                "value": "05:12-08:00"
            },
            {
                "name": "cd",
                "value": 9
            },
            {
                "name": "cf",
                "value": "s"
            },
            {
                "name": "cm",
                "value": "w"
            },
            {
                "name": "cp",
                "value": "l"
            },
            {
                "name": "d",
                "value": "2014-01-15"
            },
            {
                "name": "ft",
                "value": 1
            },
            {
                "name": "fu",
                "value": 1
            },
            {
                "name": "f",
                "value": 3
            },
            {
                "name": "hp",
                "value": -1
            },
            {
                "name": "ic",
                "value": "689714"
            },
            {
                "name": "id",
                "value": "689730"
            },
            {
                "name": "in",
                "value": 1
            },
            {
                "name": "mn",
                "value": "h"
            },
            {
                "name": "mo",
                "value": 0
            },
            {
                "name": "ms",
                "value": 4
            },
            {
                "name": "no",
                "value": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla at massa sed nulla consectetur malesuada. Aliquam a sapien non sem rhoncus bibendum quis eu tellus. Nunc luctus fermentum volutpat. Praesent tortor diam, sodales ornare facilisis sit amet, consequat nec elit. Aenean at porttitor purus. Phasellus tempus congue suscipit. Vivamus in magna ante, ut cursus mi. Quisque vel ante in massa pretium condimentum non id risus. Vivamus at felis eu enim egestas feugiat ut sit amet arcu. Nunc eu malesuada nunc. Nam felis lectus, convallis eu commodo eget, vehicula quis sem. Nulla egestas bibendum consequat. Pellentesque tristique lacus at leo dapibus pulvinar."
            },
            {
                "name": "op",
                "value": -1
            },
            {
                "name": "or",
                "value": 200
            },
            {
                "name": "ot",
                "value": "05:21-08:00"
            },
            {
                "name": "rx",
                "value": [
                    1,
                    4,
                    10
                ]
            },
            {
                "name": "vr",
                "value": 200
            },
            {
                "name": "vt",
                "value": "05:24-08:00"
            }
        ]
    }
}    
EOD;
    $control = <<<EOD
{"bf":98.6,"br":1,"bt":"05:12-08:00","cd":9,"cf":"s","cm":"w","cp":"l","d":"2014-01-15","ft":1,"fu":1,"f":3,"hp":-1,"ic":"689714","id":"689730","in":1,"mn":"h","mo":0,"ms":4,"no":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla at massa sed nulla consectetur malesuada. Aliquam a sapien non sem rhoncus bibendum quis eu tellus. Nunc luctus fermentum volutpat. Praesent tortor diam, sodales ornare facilisis sit amet, consequat nec elit. Aenean at porttitor purus. Phasellus tempus congue suscipit. Vivamus in magna ante, ut cursus mi. Quisque vel ante in massa pretium condimentum non id risus. Vivamus at felis eu enim egestas feugiat ut sit amet arcu. Nunc eu malesuada nunc. Nam felis lectus, convallis eu commodo eget, vehicula quis sem. Nulla egestas bibendum consequat. Pellentesque tristique lacus at leo dapibus pulvinar.","op":-1,"or":200,"ot":"05:21-08:00","rx":[1,4,10],"vr":200,"vt":"05:24-08:00"}
EOD;
    $payload = new Payload('application/vnd.collection+json', $subject);
    $result = CollectionJsonToJson::translate($payload);
    // print($result); die;
    $this->assertInstanceOf('\AKlump\Http\Transfer\PayloadInterface', $result);
    $this->assertSame($control, $result->getContent());
    $this->assertSame($control, (string) $result);
  }

  public function testDay() {
    $subject = <<<EOD
{
    "collection": {
        "href": "http://www.ovagraph.com/api/3/days/689730",
        "items": [
            {
                "data": [
                    {
                        "name": "title",
                        "prompt": "Title",
                        "value": "Day 9 of Cycle #13"
                    },
                    {
                        "name": "bf",
                        "prompt": "Basal temperature (f)",
                        "value": 98.6
                    },
                    {
                        "name": "br",
                        "prompt": "Breast tenderness",
                        "value": 1
                    },
                    {
                        "name": "bt",
                        "prompt": "Basal temperature time",
                        "value": "05:12-08:00"
                    },
                    {
                        "name": "cd",
                        "prompt": "Cycle day",
                        "value": 9
                    },
                    {
                        "name": "cf",
                        "prompt": "Cervical firmness",
                        "value": "s"
                    },
                    {
                        "name": "cm",
                        "prompt": "Cervical mucous",
                        "value": "w"
                    },
                    {
                        "name": "cp",
                        "prompt": "Cervical position",
                        "value": "l"
                    },
                    {
                        "name": "d",
                        "prompt": "Date",
                        "value": "2014-01-15"
                    },
                    {
                        "name": "ft",
                        "prompt": "Ferning test",
                        "value": 1
                    },
                    {
                        "name": "fu",
                        "prompt": "Frequent urination",
                        "value": 1
                    },
                    {
                        "name": "f",
                        "prompt": "OvaCue color code",
                        "value": 3
                    },
                    {
                        "name": "hp",
                        "prompt": "Home pregnancy test",
                        "value": -1
                    },
                    {
                        "name": "ic",
                        "prompt": "Cycle UUID",
                        "value": "689714"
                    },
                    {
                        "name": "id",
                        "prompt": "UUID",
                        "value": "689730"
                    },
                    {
                        "name": "in",
                        "prompt": "Intercourse",
                        "value": 1
                    },
                    {
                        "name": "mn",
                        "prompt": "Menstruation",
                        "value": "h"
                    },
                    {
                        "name": "mo",
                        "prompt": "Morning sickness",
                        "value": 0
                    },
                    {
                        "name": "ms",
                        "prompt": "Meds start day",
                        "value": 4
                    },
                    {
                        "name": "no",
                        "prompt": "Notes",
                        "value": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla at massa sed nulla consectetur malesuada. Aliquam a sapien non sem rhoncus bibendum quis eu tellus. Nunc luctus fermentum volutpat. Praesent tortor diam, sodales ornare facilisis sit amet, consequat nec elit. Aenean at porttitor purus. Phasellus tempus congue suscipit. Vivamus in magna ante, ut cursus mi. Quisque vel ante in massa pretium condimentum non id risus. Vivamus at felis eu enim egestas feugiat ut sit amet arcu. Nunc eu malesuada nunc. Nam felis lectus, convallis eu commodo eget, vehicula quis sem. Nulla egestas bibendum consequat. Pellentesque tristique lacus at leo dapibus pulvinar."
                    },
                    {
                        "name": "op",
                        "prompt": "OPK test",
                        "value": -1
                    },
                    {
                        "name": "or",
                        "prompt": "OvaCue oral reading",
                        "value": 200
                    },
                    {
                        "name": "ot",
                        "prompt": "OvaCue oral time",
                        "value": "05:21-08:00"
                    },
                    {
                        "name": "rx",
                        "prompt": "Meds/RX",
                        "value": [
                            1,
                            4,
                            10
                        ]
                    },
                    {
                        "name": "vr",
                        "prompt": "OvaCue vaginal reading",
                        "value": 200
                    },
                    {
                        "name": "vt",
                        "prompt": "OvaCue vaginal time",
                        "value": "05:24-08:00"
                    }
                ],
                "href": "http://www.ovagraph.com/api/3/days/689730",
                "links": [
                    {
                        "href": "http://www.ovagraph.com/records/1/readings/689730",
                        "name": "ovagraph_node_view",
                        "prompt": "View on OvaGraph.com",
                        "rel": "alternate",
                        "render": "link"
                    }
                ]
            }
        ],
        "template": {
            "data": [
                {
                    "name": "bf",
                    "prompt": "Basal temperature (f)",
                    "value": 98.6
                },
                {
                    "name": "br",
                    "prompt": "Breast tenderness",
                    "value": 1
                },
                {
                    "name": "bt",
                    "prompt": "Basal temperature time",
                    "value": "05:12-08:00"
                },
                {
                    "name": "cd",
                    "prompt": "Cycle day",
                    "value": 9
                },
                {
                    "name": "cf",
                    "prompt": "Cervical firmness",
                    "value": "s"
                },
                {
                    "name": "cm",
                    "prompt": "Cervical mucous",
                    "value": "w"
                },
                {
                    "name": "cp",
                    "prompt": "Cervical position",
                    "value": "l"
                },
                {
                    "name": "d",
                    "prompt": "Date",
                    "value": "2014-01-15"
                },
                {
                    "name": "ft",
                    "prompt": "Ferning test",
                    "value": 1
                },
                {
                    "name": "fu",
                    "prompt": "Frequent urination",
                    "value": 1
                },
                {
                    "name": "f",
                    "prompt": "OvaCue color code",
                    "value": 3
                },
                {
                    "name": "hp",
                    "prompt": "Home pregnancy test",
                    "value": -1
                },
                {
                    "name": "ic",
                    "prompt": "Cycle UUID",
                    "value": "689714"
                },
                {
                    "name": "id",
                    "prompt": "UUID",
                    "value": "689730"
                },
                {
                    "name": "in",
                    "prompt": "Intercourse",
                    "value": 1
                },
                {
                    "name": "mn",
                    "prompt": "Menstruation",
                    "value": "h"
                },
                {
                    "name": "mo",
                    "prompt": "Morning sickness",
                    "value": 0
                },
                {
                    "name": "ms",
                    "prompt": "Meds start day",
                    "value": 4
                },
                {
                    "name": "no",
                    "prompt": "Notes",
                    "value": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla at massa sed nulla consectetur malesuada. Aliquam a sapien non sem rhoncus bibendum quis eu tellus. Nunc luctus fermentum volutpat. Praesent tortor diam, sodales ornare facilisis sit amet, consequat nec elit. Aenean at porttitor purus. Phasellus tempus congue suscipit. Vivamus in magna ante, ut cursus mi. Quisque vel ante in massa pretium condimentum non id risus. Vivamus at felis eu enim egestas feugiat ut sit amet arcu. Nunc eu malesuada nunc. Nam felis lectus, convallis eu commodo eget, vehicula quis sem. Nulla egestas bibendum consequat. Pellentesque tristique lacus at leo dapibus pulvinar."
                },
                {
                    "name": "op",
                    "prompt": "OPK test",
                    "value": -1
                },
                {
                    "name": "or",
                    "prompt": "OvaCue oral reading",
                    "value": 200
                },
                {
                    "name": "ot",
                    "prompt": "OvaCue oral time",
                    "value": "05:21-08:00"
                },
                {
                    "name": "rx",
                    "prompt": "Meds/RX",
                    "value": [
                        1,
                        4,
                        10
                    ]
                },
                {
                    "name": "vr",
                    "prompt": "OvaCue vaginal reading",
                    "value": 200
                },
                {
                    "name": "vt",
                    "prompt": "OvaCue vaginal time",
                    "value": "05:24-08:00"
                }
            ]
        },
        "version": "1.0"
    }
}    
EOD;
    $control = <<<EOD
{"title":"Day 9 of Cycle #13","bf":98.6,"br":1,"bt":"05:12-08:00","cd":9,"cf":"s","cm":"w","cp":"l","d":"2014-01-15","ft":1,"fu":1,"f":3,"hp":-1,"ic":"689714","id":"689730","in":1,"mn":"h","mo":0,"ms":4,"no":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla at massa sed nulla consectetur malesuada. Aliquam a sapien non sem rhoncus bibendum quis eu tellus. Nunc luctus fermentum volutpat. Praesent tortor diam, sodales ornare facilisis sit amet, consequat nec elit. Aenean at porttitor purus. Phasellus tempus congue suscipit. Vivamus in magna ante, ut cursus mi. Quisque vel ante in massa pretium condimentum non id risus. Vivamus at felis eu enim egestas feugiat ut sit amet arcu. Nunc eu malesuada nunc. Nam felis lectus, convallis eu commodo eget, vehicula quis sem. Nulla egestas bibendum consequat. Pellentesque tristique lacus at leo dapibus pulvinar.","op":-1,"or":200,"ot":"05:21-08:00","rx":[1,4,10],"vr":200,"vt":"05:24-08:00"}
EOD;
    $payload = new Payload('application/vnd.collection+json', $subject);
    $result = CollectionJsonToJson::translate($payload);
    $this->assertInstanceOf('\AKlump\Http\Transfer\PayloadInterface', $result);
    $this->assertSame($control, $result->getContent());
    $this->assertSame($control, (string) $result);

  }

  public function testTextHtmlGoingInShouldFail() {
    $payload = new Payload('text/html', '<div>name: aaron</div>');
    $result = CollectionJsonToJson::translate($payload);
    $this->assertFalse($result);
  }

  public function testCollection() {
    $subject = <<<EOD
{ "collection" :
  {
    "version" : "1.0",
    "href" : "http://example.org/friends/",
    
    "links" : [
      {"rel" : "feed", "href" : "http://example.org/friends/rss"}
    ],
    
    "items" : [
      {
        "href" : "http://example.org/friends/jdoe",
        "data" : [
          {"name" : "full-name", "value" : "J. Doe", "prompt" : "Full Name"},
          {"name" : "email", "value" : "jdoe@example.org", "prompt" : "Email"}
        ],
        "links" : [
          {"rel" : "blog", "href" : "http://examples.org/blogs/jdoe", "prompt" : "Blog"},
          {"rel" : "avatar", "href" : "http://examples.org/images/jdoe", "prompt" : "Avatar", "render" : "image"}
        ]
      },
      
      {
        "href" : "http://example.org/friends/msmith",
        "data" : [
          {"name" : "full-name", "value" : "M. Smith", "prompt" : "Full Name"},
          {"name" : "email", "value" : "msmith@example.org", "prompt" : "Email"}
        ],
        "links" : [
          {"rel" : "blog", "href" : "http://examples.org/blogs/msmith", "prompt" : "Blog"},
          {"rel" : "avatar", "href" : "http://examples.org/images/msmith", "prompt" : "Avatar", "render" : "image"}
        ]
      },
      
      {
        "href" : "http://example.org/friends/rwilliams",
        "data" : [
          {"name" : "full-name", "value" : "R. Williams", "prompt" : "Full Name"},
          {"name" : "email", "value" : "rwilliams@example.org", "prompt" : "Email"}
        ],
        "links" : [
          {"rel" : "blog", "href" : "http://examples.org/blogs/rwilliams", "prompt" : "Blog"},
          {"rel" : "avatar", "href" : "http://examples.org/images/rwilliams", "prompt" : "Avatar", "render" : "image"}
        ]
      }      
    ],
    
    "queries" : [
      {"rel" : "search", "href" : "http://example.org/friends/search", "prompt" : "Search",
        "data" : [
          {"name" : "search", "value" : ""}
        ]
      }
    ],
    
    "template" : {
      "data" : [
        {"name" : "full-name", "value" : "", "prompt" : "Full Name"},
        {"name" : "email", "value" : "", "prompt" : "Email"},
        {"name" : "blog", "value" : "", "prompt" : "Blog"},
        {"name" : "avatar", "value" : "", "prompt" : "Avatar"}
        
      ]
    }
  } 
}    
EOD;
    $control = <<<EOD
[{"full-name":"J. Doe","email":"jdoe@example.org"},{"full-name":"M. Smith","email":"msmith@example.org"},{"full-name":"R. Williams","email":"rwilliams@example.org"}]
EOD;

    $payload = new Payload('application/vnd.collection+json', $subject);
    $result = CollectionJsonToJson::translate($payload);
    $this->assertInstanceOf('\AKlump\Http\Transfer\PayloadInterface', $result);
    $this->assertSame($control, $result->getContent());
    $this->assertSame($control, (string) $result);
  }


  public function testMinimal() {
    $subject = <<<EOD
{ "collection" : 
  {
    "version" : "1.0",
    "href" : "http://example.org/friends/"
  } 
}    
EOD;
    $control = '{}';
    $payload = new Payload('application/vnd.collection+json', $subject);
    $result = CollectionJsonToJson::translate($payload);
    $this->assertInstanceOf('\AKlump\Http\Transfer\PayloadInterface', $result);
    $this->assertSame('application/json', $result->getContentType());
    $this->assertSame($control, $result->getContent());
    $this->assertSame($control, (string) $result);
  }
}
