Service Provider
================

Es kommt vor, dass ein Fixture Werte von einem Service benötigt,
wie zum Beispiel einem Faker Service, der Fake Daten generiert.

Um dieses Problem zu lösen gibt es einige möglichkeiten, wie zum Beispiel
einen eigenen Converter oder Event Listener zu schreiben. Der einfachste Weg
ist es aber einen Service über den Service Provider hinzuzufügen.

Add a service
-------------

Einen Service kann man ganz einfach über den FixtureManager registrieren.

``` php
use DavidBadura\Fixtures\FixtureManager\FixtureManager;

$fixtureManager = FixtureManager::createDefaultFixtureManager($objectManager);

$faker = \Faker\Factory::create();
$fixtureManager->addService('faker', $faker);

```

Use a service
-------------

Nach dem der Service registriert worde ist, kann diese im Fixture mit folgenden Schema
verwenden <{ServiceName}::{MethodName}({Attributes]}>

Hier ist ein Beispiel:

``` yaml
# install.yml
user:
    properties:
        class: "YourBundle\Entity\User"
    data:
        david:
            name: <faker::name()>
            email: <faker::email()>
```

Dies wird wie folgt umgewandelt:

``` yaml
# install.yml
user:
    properties:
        class: "YourBundle\Entity\User"
    data:
        david:
            name: Max Mustermann
            email: max@example.de
```


Complex usecase
---------------

Ein komplexeres Beispiel:

``` yaml
# install.yml
user:
    properties:
        class: "YourBundle\Entity\User"
    data:
        user{0..1}:
            name: <faker::name()>
            email: <faker::email()>
            groups: ["@group:group{0..1}"]
            notice: "<faker::sentence(5)>"

group:
    properties:
        class: "YourBundle\Entity\Group"
    data:
        group{0..1}:
            name: <faker::name()>
```

will be convertet to:

``` yaml
# install.yml
user:
    properties:
        class: "YourBundle\Entity\User"
    data:
        user0:
            name: Max Mustermann
            email: test@googlemail.com
            groups: ["@group:group1"]
            notice: "Sit vitae voluptas sint non voluptates."
        user1:
            name: Franz Müller
            email: example@yahoo.com
            groups: ["@group:group1"]
            notice: "Sit vitae voluptas sint non voluptates."

group:
    properties:
        class: "YourBundle\Entity\Group"
    data:
        group0:
            name: Test
        group1:
            name: Admin
```

The Faker Generator has a lot of methods. For more information read the [documentation](https://github.com/fzaninotto/Faker).