Converter
=========

Fixture converters transform the fixture data in objects.

DefaultConverter
----------------

The standard converter uses the setter methods of the class.
It can also works with classes that have a constructor and has some more features.

The default converter needs the `class` property so that it knows which class to be initialized.

``` yaml
fixtures:
    user:
        properties:
            class: 'YourBundle\Entity\User'
        data:
            # ...
```

The converter handles all attributes and he trying to pass values ​​to the object.

``` yaml
fixtures:
    user:
        properties:
            class: 'YourBundle\Entity\User'
        data:
            david:
                name: 'David Badura'
                email: 'd.badura@gmx.de'
```

Order to pass data:
- $object->set{PropertyName}()
- $object->add{PropertyName}() (foreach)
- $object->get{PropertyName}() (instanceof ArrayCollection)
- $object->{PropertyName}
- $object->__set()

If you have a class with a constructor you can add the `constructor` property.

``` yaml
fixtures:
    user:
        properties:
            class: 'YourBundle\Entity\User'
            constructor: [name, email]
        data:
            david:
                name: 'David Badura'
                email: 'd.badura@gmx.de'
```

Also you can mark optional constructor attributes with a `?` symbole.

``` yaml
fixtures:
    user:
        properties:
            class: 'YourBundle\Entity\User'
            constructor: [name, ?email]
        data:
            david:
                name: 'David Badura'
```

The DefaultConverter support \DateTime parameters in setter and adder methods:


Method example `$object->setCreateDate(\DateTime $date)`

``` yaml
fixtures:
    user:
        properties:
            class: 'YourBundle\Entity\User'
            constructor: [name, ?email]
        data:
            david:
                name: 'David Badura'
                createDate: 'now'
```


Create your own converter
-------------------------

You can also implement your own Converter.
The Converter must extends the `DavidBadura\FixturesBundle\FixtureConverter\FixtureConverter` class.
The fixture converter are loaded automatically from `YourBundle\FixtureConverter` folder.

``` php
// YourBundle/FixtureConverter/UserConverter.php
namespace YourBundle\FixtureConverter;

use DavidBadura\FixturesBundle\FixtureConverter\FixtureConverter;
use DavidBadura\FixturesBundle\FixtureData;

class UserConverter extends FixtureConverter
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

In your fixture files your fixtures must add/change the converter property in `user`.

``` yaml
fixtures:
    user:
        converter: user
        data: # ...
```

Your converter can also access the properties section.

``` yaml
fixtures:
    user:
        converter: user
        properties:
            foo: bar
        data: # ...
```

``` php
// YourBundle/FixtureConverter/BazConverter.php
namespace YourBundle\FixtureConverter;

use DavidBadura\FixturesBundle\FixtureConverter\FixtureConverter;
use DavidBadura\FixturesBundle\FixtureData;

class BazConverter extends FixtureConverter
{

    public function createObject(FixtureData $data)
    {
        $properties = $data->getProperties();
        $properties['foo'] # bar
        // ...
    }

    // ...

}
```

To resolve bidrectional references you can overwrite the `finalizeObject` method.

``` php
// YourBundle/FixtureConverter/UserConverter.php
namespace YourBundle\FixtureConverter;

use DavidBadura\FixturesBundle\FixtureConverter\FixtureConverter;
use DavidBadura\FixturesBundle\FixtureData;

class GroupConverter extends FixtureConverter
{

    public function createObject(FixtureData $fixtureData)
    {
        $data = fixtureData->getData();

        $group = new Group();

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

Data validation (config tree)
-----------------------------

``` php
// YourBundle/FixtureConverter/UserConverter.php
namespace YourBundle\FixtureConverter;

use DavidBadura\FixturesBundle\FixtureConverter\FixtureConverter;
use DavidBadura\FixturesBundle\FixtureConverter\ConverterDataValidate;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class UserConverter extends FixtureConverter implements ConverterDataValidate
{

    // ...

    public function addNodeSchema(NodeBuilder $node)
    {
        $node->scalarNode('name')->isRequired()->end()
             ->scalarNode('email')->end()
    }

}
```


Converter as a service
----------------------

To register a converter as a service, you must add the `davidbadura_fixtures.converter` tag to your service.

``` xml
<services>
    <service id="your_bundle.converter.user" class="YourBundle\FixtureConverter\UserConverter">
        <tag name="davidbadura_fixtures.converter" />
    </service>
</services>
```
