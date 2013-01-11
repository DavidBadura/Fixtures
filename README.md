davidbadura \ fixtures
======================

[![Build Status](https://secure.travis-ci.org/DavidBadura/Fixtures.png)](http://travis-ci.org/DavidBadura/Fixtures)

*NOT STABLE YET!*


This is a porting of [DavidBaduraFixturesBundle](https://github.com/DavidBadura/FixturesBundle).
In the first step, the main components are moved into a separate [davidbadura\fixtures](https://github.com/DavidBadura/Fixtures) library.
After that must write a lot of tests...

Features:

* [fzaninotto/Faker](https://github.com/fzaninotto/Faker), a PHP library that generates fake data for you.
* Resolve object dependency (also bidirectional references)
* Configurable default fixture converter (constructor, properties, set* and add* methods)
* Easy to create your own converter
* Extendable by events (currently with symfony\event-dispatcher)
* Fixture filtering by tags
* Object validation (currently with symfony\validator)
* Fixturemanager as a service
* Load fixtures over the console
* MongoDB support
* DefaultConverter: handle "setCreateDate(\DateTime $date)" methods