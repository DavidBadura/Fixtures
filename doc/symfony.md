Symfony
=======

1. Installation
---------------

Add DavidBadura Fixtures in your composer.json

``` js

{
    "require": {
        "davidbadura/fixtures": "~1.0"
    }
}

```

Add the DavidBaduraFixturesBundle to your application kernel:

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new DavidBadura\Fixtures\Extension\Symfony\DavidBaduraFixturesBundle(),
        // ...
    );
}
```

2. Configuration
----------------

Configure DavidBaduraFixturesBundle:

``` yaml
# app/config/config.yml
david_badura_fixtures:
  bundles: [AppBundle]
```

Activate support for MongoDB:

``` yaml
# app/config/config.yml
david_badura_fixtures:
  persister: odm
```


3. Create fixtures
---------------

Now you should create your fixture data:

``` yaml
# @AppBundle/Resource/fixtures/install.yml
user:
    properties:
        class: "AppBundle\Entity\User"
    data:
        david:
            name: David
            email: "d.badura@gmx.de"
            groups: ["@group:admin"] # <- reference to group.admin

group:
    properties:
        class: "AppBundle\Entity\Group"
    data:
        admin:
            name: Admin
        member:
            name: Member
```
The fixture files will be automatically loaded from the `Resources\fixtures` folder.

4. Load fixtures
----------------

Command:

``` shell
php app/console davidbadura:fixtures:load
```

Service:
``` php
$fixtureManager = $container->get('davidbadura_fixtures.fixture_manager');
$fixtureManager->load();
```
