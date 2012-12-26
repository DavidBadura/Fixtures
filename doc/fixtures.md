Fixtures
========

Simple fixtures
---------------

``` yaml
# @YourBundle/Resource/fixtures/install.yml
fixtures:
    user:
        properties:
            class: "YourBundle\Entity\User"
        data:
            david:
                name: David
                email: "d.badura@gmx.de"
```

The fixture files will be automatically loaded from the `Resources\fixtures` folder.

References
----------

You can add references in your fixtures with a `@` prefix.

``` yaml
# @YourBundle/Resource/fixtures/install.yml
fixtures:
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

To add bidrectional references you can add a `@@` prefix.

``` yaml
# @YourBundle/Resource/fixtures/install.yml
fixtures:
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
fixtures:
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

To change the converter you must change the `converter` property.

``` yaml
# @YourBundle/Resource/fixtures/install.yml
fixtures:
    user:
        converter: default
        data: #...
```

You can read more about converter in [Converter](converter.md).

Object validation
-----------------

Object validation is enabled by default.
To disable validation `validation.enable` must be set `false`.
You can also define validation groups.

``` yaml
# @YourBundle/Resource/fixtures/install.yml
fixtures:
    user:
        validation:
            enable: true
            groups: [Default]
        data: #...
```

Tags
----

You can give your fixtures some tags, then you can filter these.

``` yaml
# @YourBundle/Resource/fixtures/install.yml
fixtures:
    user:
        tags: [install, test]
        data: #...
```