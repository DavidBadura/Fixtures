Fixtures
========

Fixtures can be loaded from many formats.
The formats that are supported are;

 - JSON
 - PHP
 - TOML
 - YAML

The Yaml format is used here as an example.

The DefaultConverter is used in the standard FixtureManager configuration.

Simple fixtures
---------------

This is a very simple example.

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
First, the executor resolve the "single @" references, then the "double @" reference.

``` yaml
# install.yml
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
# install.yml
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


The DefaultConverter is used in the standard FixtureManager configuration.
This can you change over the `converter` property.

``` yaml
# install.yml
user:
    converter: default
    data: #...
```

You can read more about converter in [Converter](converter.md).

Tags
----

You can give your fixtures some tags

``` yaml
# install.yml
user:
    tags: [install, test]
    data: #...
```

Then you can use the tags to filter the fixtures

``` php
use DavidBadura\Fixtures\FixtureManager\FixtureManager;

$fixtureManager = FixtureManager::createDefaultFixtureManager($objectManager);

$fixtureManager->load('path/to/fixtures', array('tags' => array('install')));
```
