Service
=======

The fixture manager controls everything and has the service id `david_badura_fixtures.fixture_manager`.

``` php
$fixtureManager = $this->get('david_badura_fixtures.fixture_manager');
```

To load fixtures you just have to execute the load method.

``` php
$fixtureManager->load();
```

You can also add parameters to control the data loading.

Paramenters:

- tags: filtering fixtures by tags
- fixtures: influenced the fixture folder
- test: simulate loading fixtures

examples:

``` php
$this->get('david_badura_fixtures.fixture_manager')->load(array('tags' => array('install', 'dev')));
$this->get('david_badura_fixtures.fixture_manager')->load(array('fixtures' => array('src/...')));
```

For more informations about the optional parameters you can read the [command](command.md) section.