Command
=======

load fixtures
-------------

``` shell
php app/console davidbadura:fixtures:load
```

optional Attributes
-------------------

*filtering by tags*

``` shell
php app/console davidbadura:fixtures:load -tag install
php app/console davidbadura:fixtures:load -tag install -tag test
```

*load fixtures form special folder*

``` shell
php app/console davidbadura:fixtures:load -fixture "src/.../fixtures"
php app/console davidbadura:fixtures:load -fixture "src/.../fixtures" -fixture "src/.../fixtures2"
```

You can also load only one file.

``` shell
php app/console davidbadura:fixtures:load -fixture "src/.../fixtures/test.yml"
```

*simulate process*

You can simulate to load fixtures.
The process is run, but the objects are not persisted

``` shell
php app/console davidbadura:fixtures:load -test"
```