FixtureManager
==============

Der FixtureManager kümmert sich um den gesamten Ablauf.
Er ist aus folgenden Komponenten aufgebaut und jedes dieser kann ausgetauscht werden:

* **Loader** ist für das laden der Fixture Resources zuständig und gibt eine FixtureCollection zurück
* **Executor** löst abhängigkeiten auf und erzeugt die Objekte
* **Persister** speichert die Daten in einer Datenbank
* **ServiceProvider** liefert verschiedene Service, die in Fixtures verwendet werden können
* **EventDispatcher** gibt möglichkeiten sich in verschiedenen stellen einzuhacken

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