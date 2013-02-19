davidbadura \ fixtures
======================

[![Build Status](https://secure.travis-ci.org/DavidBadura/Fixtures.png)](http://travis-ci.org/DavidBadura/Fixtures)

This is a porting of [DavidBaduraFixturesBundle](https://github.com/DavidBadura/FixturesBundle).

Features:

* Use Services like [fzaninotto/Faker](https://github.com/fzaninotto/Faker), a PHP library that generates fake data for you.
* Resolve object dependency automatically (also bidirectional references).
* Configurable default fixture converter (constructor, properties, set* and add* methods).
* Easy to create your own converter.
* Many fixtures loader: Yaml, Json und PHP (XML is coming soon).
* Extendable by events (currently with symfony\event-dispatcher).
* Fixture filtering by tags
* Object validation (currently with symfony\validator)
* Persist Fixtures with Doctrine ORM or Doctrine MongoDb (Propel is coming soon).
* Easy to add your own Persister.

Todos:

* Write more tests.
* Add XML loader.
* Add Propel.
* Add cli functionality.
* Translate documentation ( my english is really bad ;) )
* Write documentation


Documentation
-------------

* [FixtureManager](https://github.com/DavidBadura/Fixtures/blob/master/doc/fixture_manager.md)
* [Fixtures](https://github.com/DavidBadura/Fixtures/blob/master/doc/fixtures.md)
* [Loader](https://github.com/DavidBadura/Fixtures/blob/master/doc/loader.md)
* [Converter](https://github.com/DavidBadura/Fixtures/blob/master/doc/converter.md)
* [ServiceProvider](https://github.com/DavidBadura/Fixtures/blob/master/doc/service_provider.md)

Useage
------

First, you must create fixtures files in yaml, json, php or mixed.

YAML:

```yaml

user:
  properties:
    class: DavidBadura\Fixtures\TestObjects\User
    constructor: [name, email]
  data:
    david:
      name: "David Badura"
      email: "d.badura@gmx.de"
      group: ["@group:owner", "@group:developer"]
      role: ["@role:admin"]
    other:
      name: "Somebody"
      email: "test@example.de"
      group: ["@group:developer"]
      role: ["@role:user"]


```

PHP:

```php

<?php

return array(
            'role' =>
            array(
                'properties' =>
                array(
                    'class' => 'DavidBadura\\Fixtures\\TestObjects\\Role',
                ),
                'data' =>
                array(
                    'admin' =>
                    array(
                        'name' => 'Admin',
                    ),
                    'user' =>
                    array(
                        'name' => 'User',
                    ),
                ),
            )
        );


```

JSON:

```json

{
    "group": {
        "properties": {
            "class": "DavidBadura\\Fixtures\\TestObjects\\Group"
        },
        "data": {
            "developer": {
                "name": "Developer",
                "leader": "@@user:david"
            }
        }
    }
}

```

Now, you can load the fixtures, crate the objects and persist in the database

```php

use DavidBadura\Fixtures\FixtureManager\FixtureManager;

// $objectManager can be curently Doctrine ORM Entity Manager or Doctrine MongoDb DocumentManager
$fixtureManager = FixtureManager::createDefaultFixtureManager($objectManager);

$fixtureManager->load('path/to/fixtures');

```