<?php declare(strict_types=1);

namespace DavidBadura\Fixtures;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureEvents
{
    public const onPreLoad = 'david_badura_fixtures.pre_load';

    public const onPostLoad = 'david_badura_fixtures.post_load';

    public const onPreExecute = 'david_badura_fixtures.pre_execute';

    public const onPostExecute = 'david_badura_fixtures.post_execute';

    public const onPostPersist = 'david_badura_fixtures.post_persist';
}
