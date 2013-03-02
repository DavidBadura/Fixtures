Load Fixtures
=============

First, you must create fixtures files in yaml, json, php, toml or mixed.

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

Now, you can load your fixtures with a compatible loader.

```php

$yamlLoader = new DavidBadura\Fixtures\Loader\YamlLoader();
$collection = $yamlLoader->load('user.yml');

$jsonLoader = new DavidBadura\Fixtures\Loader\JsonLoader();
$collection->merge($jsonLoader->load('group.json'));

$arrayLoader = new DavidBadura\Fixtures\Loader\ArrayLoader();
$collection->merge($arrayLoader->load('roles.php'));

```

Better, you can use the chain loader and then, you can add loaders, which you are needed.

```php

$loader = new DavidBadura\Fixtures\Loader\ChainLoader(array(
    new DavidBadura\Fixtures\Loader\YamlLoader(),
    new DavidBadura\Fixtures\Loader\JsonLoader(),
    new DavidBadura\Fixtures\Loader\ArrayLoader()
));

$collection = $loader->load('./');

```

You can also use the toml loader.

```toml

[user.properties]
class = "DavidBadura\\Fixtures\\TestObjects\\User"
constructor = ["name", "email"]

[user.data.david]
name = "David Badura"
email = "d.badura@gmx.de"
group = ["@group:owner", "@group:developer"]
role = ["@role:admin"]

[user.data.other]
name = "Somebody"
email = "test@example.de"
group = ["@group:developer"]
role = ["@role:user"]

```