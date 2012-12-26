Configuration
=============

Minimal configuration
---------------------

You must only add your bundle in `bundles`, so your fixtures and converters can be found.

``` yaml
# app/config/config.yml
david_badura_fixtures:
  bundles: [YourBundle]
```

Activate MongoDB support
------------------------

``` yaml
# app/config/config.yml
david_badura_fixtures:
  persister: odm
```


Defaults
--------

``` yaml
# app/config/config.yml
david_badura_fixtures:
  bundles: []
  persister: orm
  defaults:
    converter: default
    validation:
        enable: true
        group: default
```