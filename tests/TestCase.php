<?php

namespace ZFort\SocialAuth\Test;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Exceptions\Handler;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use ZFort\SocialAuth\Test\Utils\SocialiteMock;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * @var string
     */
    protected $userEmail = 'test@user.com';

    /**
     * @var array
     */
    protected $social = ['social' => 'facebook'];

    /**
     * @var SocialiteMock
     */
    protected $socialiteMock;

    public function setUp()
    {
        parent::setUp();

        config(['auth.providers.users.model' => User::class]);
        $this->setUpDatabase($this->app);

        $this->socialiteMock = new SocialiteMock($this->app, $this->userEmail);
        $this->socialiteMock->create();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [\ZFort\SocialAuth\SocialAuthServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('avatar');
        });
        include_once __DIR__ . '/../resources/database/migrations/create_social_providers_table.php.stub';

        (new \CreateSocialProvidersTable())->up();

        User::create(['email' => $this->userEmail, 'avatar' => '']);
        $app[config('social-auth.models.social')]->create(['slug' => 'facebook', 'label' => 'Facebook']);
        $app[config('social-auth.models.social')]->create(['slug' => 'google', 'label' => 'Google+']);
    }

    /**
     * @return User
     */
    public function getTestUser(): User
    {
        return User::whereEmail($this->userEmail)->first();
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(\Exception $e) {}
            public function render($request, \Exception $e) {
                throw $e;
            }
        });
    }
}
