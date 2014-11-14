<?php namespace Jai\Authentication\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Jai\Authentication\Tests\Unit\Stubs\NullLogger;
use \Orchestra\Testbench\TestCase as OrchestraTestCase;
/**
 * Test TestCase
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
class TestCase extends OrchestraTestCase  {

    public function setUp()
    {
        parent::setUp();

        require_once __DIR__ . "/../../src/routes.php";

        $this->useMailPretend();
        $this->useNullLogger();
    }

    public function useNullLogger()
    {
        \Mail::setLogger(new NullLogger());
    }

    protected function getPackageProviders()
    {
        return [
                'Cartalyst\Sentry\SentryServiceProvider',
                'Jai\Authentication\AuthenticationServiceProvider',
                'Jai\Library\LibraryServiceProvider',
            ];
    }

    protected function getPackageAliases()
    {
        return [
            'Sentry' => 'Cartalyst\Sentry\Facades\Laravel\Sentry',
        ];
    }

    protected function getNowDateTime()
    {
        return Carbon::now()->toDateTimeString();
    }

    /**
     * @param $class
     */
    protected function assertHasErrors($class)
    {
        $this->assertFalse($class->getErrors()->isEmpty());
    }

    protected function useMailPretend()
    {
        Config::set('mail.pretend', true);
    }

    /**
     * @test
     **/
    public function dummy() {}
}
 