Fixtures
========

Fixtures können aus vielseitigen Formaten geladen werden.
Welche Formate unterstützt werden findet Ihr unter Loader.
Hier wird als Beispiel das Yaml Format verwendet.

In der Standard Konfiguration des FixtureManagers wird der DefaultConverter verwendet.

Simple fixtures
---------------

Ein ganz simples Beispiel sieht wie folgt aus:

``` yaml
# install.yml
user:
    properties:
        class: "YourBundle\Entity\User"
    data:
        david:
            name: David
            email: "d.badura@gmx.de"
```

References
----------

You can add references to other fixture.
This can you do with the `@` prefix, like this example:

``` yaml
# install.yml
user:
    properties:
        class: "YourBundle\Entity\User"
    data:
        david:
            name: David
            email: "d.badura@gmx.de"
            groups: ["@group:admin"] # <- reference to group.admin

group:
    properties:
        class: "YourBundle\Entity\Group"
    data:
        admin:
            name: Admin
        member:
            name: Member
```

Bidrectional references
-----------------------

To add bidrectional references you can use the `@@` prefix.
First, the executor resolve the "single @" references,
then the "double @" reference.

``` yaml
# @YourBundle/Resource/fixtures/install.yml
user:
    properties:
        class: "YourBundle\Entity\User"
    data:
        david:
            name: David
            email: "d.badura@gmx.de"
            groups: ["@group:admin"]

group:
    properties:
        class: "YourBundle\Entity\Group"
    data:
        admin:
            ladder: "@@user:david"
            name: Admin
        member:
            ladder: "@@user:david"
            name: Member
```

Unique IDs
----------

To generate a unique ID you can use the `{unique_id}` snippet.

``` yaml
# @YourBundle/Resource/fixtures/install.yml
user:
    properties:
        class: "YourBundle\Entity\User"
    data:
        david:
            name: Random {unique_id}
            email: "{unique_id}@example.com"
```

Change converter
----------------


In der Standard Konfiguration des FixtureManagers wird der DefaultConverter verwendet.
This can you change over the `converter` property.

``` yaml
# @YourBundle/Resource/fixtures/install.yml
user:
    converter: default
    data: #...
```

You can read more about converter in [Converter](converter.md).

Tags
----

You can give your fixtures some tags.
Over the Tags you can filter the fixtures.

``` yaml
# @YourBundle/Resource/fixtures/install.yml
user:
    tags: [install, test]
    data: #...
```

So kann man die Tags verwenden:

``` php
use DavidBadura\Fixtures\FixtureManager\FixtureManager;

$fixtureManager = FixtureManager::createDefaultFixtureManager($objectManager);

$fixtureManager->load('path/to/fixtures', array('tags' => array('install')));
```