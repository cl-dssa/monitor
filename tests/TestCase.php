<?php

namespace Tests;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Fluent;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var bool
     */
    private static $setUpHasRunOnce;

    protected function setUp():void
    {
        $this->hotfixSqlite();
        parent::setUp();
        $this->withoutExceptionHandling();
        if (!static::$setUpHasRunOnce) {
            $this->artisan('migrate:fresh');
            $this->seed([\RegionSeeder::class, \CommuneSeeder::class,\EstablishmentSeeder::class]);
            static::$setUpHasRunOnce = true;
        }
    }

    public function hotfixSqlite()
    {
        Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
            return new class($connection, $database, $prefix, $config) extends SQLiteConnection {
                public function getSchemaBuilder()
                {
                    if ($this->schemaGrammar === null) {
                        $this->useDefaultSchemaGrammar();
                    }
                    return new class($this) extends SQLiteBuilder {
                        protected function createBlueprint($table, Closure $callback = null)
                        {
                            return new class($table, $callback) extends Blueprint {
                                public function dropForeign($index)
                                {
                                    return new Fluent();
                                }
                            };
                        }
                    };
                }
            };
        });
    }
}
