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

Man kann sich ganz einfach einen Default FixtuerManager bauen lassen

Ein ganz simples Beispiel sieht wie folgt aus:

``` php

use DavidBadura\Fixtures\FixtureManager\FixtureManager;

// $objectManager can be curently Doctrine ORM Entity Manager or Doctrine MongoDb DocumentManager
$fixtureManager = FixtureManager::createDefaultFixtureManager($objectManager);

```

Die statische Methode `FixtureManager::createDefaultFixtureManager` erwartet einen Persister
kompatibles Objekt. Zurzeit werden alle Doctrine ObjectManager unterstützt wie zum Beispiel
Doctrine ORM, Doctrine Mongo ODM oder Doctrine Couch ODM.

Um einen eigenen Persister zu erstellen lese dazu die folgende Dokumentation: Persister.

Use FixtureManager
------------------

Den FixtureManager kann man ganz einfach verwenden. Als erster Parameter kann der Pfad
zu den Fixtures übergeben werden und als zweiter weiter Optionen.

``` php

use DavidBadura\Fixtures\FixtureManager\FixtureManager;

$fixtureManager = FixtureManager::createDefaultFixtureManager($objectManager);

$fixtureManager->load('path/to/fixtures', array('tags' => array('install')));

```

Verfügbare Optionen:

* **tags** filtert die fixtures
* **dry_run** testet den ablauf ohne das Ergebnis zu speichern
