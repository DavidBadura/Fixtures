FixtureManager
==============

The FixtureManager handles the main process.
It consists of the following components and each of these can be replaced:

* **Loader** load the fixtures (yaml, toml, php etc.) and return it as a normalized FixtureCollection object
* **Executor** resolves the references and passes the data to the converter to create objects
* **Persister** save the objects, in example in a database
* **ServiceProvider** provides services which you can use in fixtures
* **EventDispatcher** gives you the possibilty to hook in the process

Default FixtureManager
----------------------

A very simple example to create the default FixtureManager

``` php

use DavidBadura\Fixtures\FixtureManager\FixtureManager;

// $objectManager can be curently Doctrine ORM Entity Manager or Doctrine MongoDb DocumentManager
$fixtureManager = FixtureManager::createDefaultFixtureManager($objectManager);

```

The static method `FixtureManager::createDefaultFixtureManager` expects a persister compatible object. 
All Doctrine ObjectManagers are supported as for example
Doctrine ORM, Doctrine Mongo ODM oder Doctrine Couch ODM.

To create your own persister, read the persister documentation.

Use FixtureManager
------------------

The FixtureManager is easy to use. 
The first parameter is the path to the fixtures. The second is the options.

``` php

use DavidBadura\Fixtures\FixtureManager\FixtureManager;

$fixtureManager = FixtureManager::createDefaultFixtureManager($objectManager);

$fixtureManager->load('path/to/fixtures', array('tags' => array('install')));

```

Available Options:

 - **tags** filters the fixtures
 - **dry_run** tests the sequence without storing the result
