Converter
=========

Fixture converters transform the fixture data (array) to objects.

DefaultConverter
----------------

The library provide a DefaultConverter, that pass the data via setter and adder methods.
It can also works with classes that have a constructor and has some more features like create DateTime object by typehinting.

The default converter needs the `class` property so that it knows which class should be initialized.

``` yaml
user:
    properties:
        class: 'YourBundle\Entity\User'
    data:
        # ...
```

The converter handles all attributes and it trying to pass the values to the object.

``` yaml
user:
    properties:
        class: 'YourBundle\Entity\User'
    data:
        david:
            name: 'David Badura'
            email: 'd.badura@gmx.de'
```

In this example has the DefaultConverter following workflow:

``` php

$object = new YourBundle\Entity\User();
$object->setName('David Badura');
$object->setEmail('d.badura@gmx.de');
return $object;

```

With the following sequence the DefaultConterver tried to pass the data:

* `$object->set{PropertyName}({value})`
* `$object->add{PropertyName}({value.element})` wrapped in foreach if the value is an array
* `$object->get{PropertyName}()` and then add with `add({value})` method (instanceof ArrayCollection)
* `$object->{PropertyName} = {value}`
* `$object->__set({key}, {value})`

If you have a class with a constructor you can add the `constructor` property. You must define which property are passed in the constructor (the order is important!).

``` yaml
user:
    properties:
        class: 'YourBundle\Entity\User'
        constructor: [name, email]
    data:
        david:
            name: 'David Badura'
            email: 'd.badura@gmx.de'
```

DefaultConverter workflow:

``` php

$object = new YourBundle\Entity\User('David Badura', 'd.badura@gmx.de');
return $object;

```

Also you can mark optional constructor attributes with a `?` symbole.

``` yaml
user:
    properties:
        class: 'YourBundle\Entity\User'
        constructor: [name, ?email]
    data:
        david:
            name: 'David Badura'
```

The DefaultConverter support \DateTime parameters in setter and adder methods:
In this example provide the User Entity this method `YourBundle\Entity\User::setCreateDate(\DateTime $date)`

``` yaml
user:
    properties:
        class: 'YourBundle\Entity\User'
        constructor: [name, ?email]
    data:
        david:
            name: 'David Badura'
            createDate: 'now'
```

DefaultConverter workflow:

``` php

$object = new YourBundle\Entity\User('David Badura');
$object->setCreateDate(new \DateTime('now'));
return $object;

```


Create your own converter
-------------------------

You can also implement your own Converter.
The Converter must extends the `DavidBadura\Fixtures\Converter\AbstractConverter` class
or implement the `DavidBadura\Fixtures\Converter\ConverterInterface` interface.

``` php
// UserConverter.php

use DavidBadura\Fixtures\Converter\AbstractConverter;
use DavidBadura\Fixtures\Fixture\FixtureData;

class UserConverter extends AbstractConverter
{

    public function createObject(FixtureData $fixtureData)
    {
        $data = $fixtureData->getData();

        $user = new User($data['name'], $data['email']);
        foreach ($data['groups'] as $group) {
            $user->addGroup($group);
        }

        return $user;
    }

    public function getName()
    {
        return 'user';
    }
}
```

Now you must register the converter to use it:

``` php

use DavidBadura\Fixtures\FixtureManager\FixtureManager;

$userConverter = new \UserConverter();

$fixtureManager = FixtureManager::createDefaultFixtureManager($objectManager);
$fixtureManager->getExecutor()->addConverter($userConverter);

```

In the last step, your fixture files must add/change the converter property in `user`.

``` yaml
user:
    converter: user
    data: # ...
```

Your converter can also access the properties parameters.

``` yaml
user:
    converter: user
    properties:
        foo: bar
    data: # ...
```

``` php
// BazConverter.php

use DavidBadura\Fixtures\Converter\AbstractConverter;
use DavidBadura\Fixtures\Fixture\FixtureData;

class BazConverter extends AbstractConverter
{

    public function createObject(FixtureData $data)
    {
        $properties = $data->getProperties();
        $properties->get('foo'); # bar
        // ...
    }

    // ...

}
```

To resolve bidrectional references you can overwrite the `finalizeObject` method.

``` php
// GroupConverter.php

use DavidBadura\Fixtures\Converter\AbstractConverter;
use DavidBadura\Fixtures\Fixture\FixtureData;

class GroupConverter extends AbstractConverter
{

    public function createObject(FixtureData $data)
    {
        $data = $fixtureData->getData();

        $group = new Group($data['name']);

        return $group;
    }

    public function finalizeObject($object, FixtureData $data)
    {
        $data = $fixtureData->getData();
        $object->addLadder($data['ladder']);
    }

    public function getName()
    {
        return 'group';
    }
}
```
