<?php  namespace Jai\Authentication\Tests\Unit;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Jai\Authentication\Exceptions\AuthenticationErrorException;
use Jai\Authentication\Tests\Unit\Traits\Helper;
use Jai\Authentication\Tests\Unit\Traits\UserFactory;
use Mockery as m;
use App;

/**
 * Test AuthControllerTest
 *
 * @author jai beschi jai@Jaibeschi.com
 */
class AuthControllerTest extends DbTestCase
{
    use Helper, UserFactory;

    protected $current_user;
    protected $current_email;

    public function setUp()
    {
        parent::setUp();
        $this->initializeUserHasher();
        $this->current_user = $this->make('Jai\Authentication\Models\User', $this->getUserStub())->first();
        $this->current_email = $this->current_user->email;
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     **/
    public function it_login_client_with_success()
    {
        $email = "mail@mail.com";
        $password = "pass";
        $remember = "1";
        $this->mockAuthenticatorSuccess($email, $password, $remember);

        $this->action('POST', 'Jai\Authentication\Controllers\AuthController@postClientLogin', [
                "email"    => $email,
                "password" => $password,
                "remember" => $remember
        ]);

        $this->assertRedirectedTo('/');
    }

    /**
     * @test
     **/
    public function it_login_admin_with_success()
    {
        $email = "mail@mail.com";
        $password = "pass";
        $remember = "1";
        $this->mockAuthenticatorSuccess($email, $password, $remember);

        $this->action('POST', 'Jai\Authentication\Controllers\AuthController@postAdminLogin', [
                "email"    => $email,
                "password" => $password,
                "remember" => $remember
        ]);

        $this->assertRedirectedTo('/admin/users/dashboard');
    }

    /**
     * @param $email
     * @param $password
     * @param $remember
     */
    private function mockAuthenticatorSuccess($email, $password, $remember)
    {
        $mock_authenticator_success = m::mock('StdClass')->shouldReceive('authenticate')->with([
                                                                                                       "email"    => $email,
                                                                                                       "password" => $password
                                                                                               ], $remember)->getMock();
        App::instance('authenticator', $mock_authenticator_success);
    }

    /**
     * @test
     **/
    public function it_login_client_with_error()
    {
        $email = "mail@mail.com";
        $password = "pass";
        $remember = "1";

        $this->mockAuthenticationFails($email, $password, $remember);

        $this->action('POST', 'Jai\Authentication\Controllers\AuthController@postClientLogin', [
                "email"    => $email,
                "password" => $password,
                "remember" => $remember
        ]);

        $this->assertRedirectedToAction('Jai\Authentication\Controllers\AuthController@getClientLogin');
        $this->assertSessionHasErrors();
    }

    /**
     * @test
     **/
    public function it_login_admin_with_error()
    {
        $email = "mail@mail.com";
        $password = "pass";
        $remember = "1";

        $this->mockAuthenticationFails($email, $password, $remember);

        $this->action('POST', 'Jai\Authentication\Controllers\AuthController@postAdminLogin', [
                "email"    => $email,
                "password" => $password,
                "remember" => $remember
        ]);

        $this->assertRedirectedToAction('Jai\Authentication\Controllers\AuthController@getAdminLogin');
        $this->assertSessionHasErrors();
    }

    /**
     * @param $email
     * @param $password
     * @param $remember
     */
    private function mockAuthenticationFails($email, $password, $remember)
    {
        $mock_authenticator_fail = m::mock('StdClass')->shouldReceive('authenticate')->with([
                                                                                                    "email"    => $email,
                                                                                                    "password" => $password
                                                                                            ], $remember)->once()
                                    ->andThrow(new AuthenticationErrorException())->shouldReceive('getErrors')->once()->andReturn([])->getMock();
        App::instance('authenticator', $mock_authenticator_fail);
    }

    /**
     * @test
     **/
    public function it_process_recovery_data_and_redirect_with_success()
    {
        StateKeeper::set('expected_to', $this->current_email);
        StateKeeper::set('expected_subject', 'Password recovery request');
        StateKeeper::set('expected_body', 'We received a request to change your password, if you authorize it');

        Event::listen('mailer.sending', 'Jai\Authentication\Tests\Unit\AuthControllerTest@checkForSingleMailData');

        $this->action('POST', 'Jai\Authentication\Controllers\AuthController@postReminder', ["email" => $this->current_email]);
        $this->assertRedirectedTo('/user/reminder-success');
    }

    /**
     * @test
     **/
    public function it_process_recovery_and_show_errors()
    {
        $mock_reminder_service = m::mock('Jai\Authentication\Services\ReminderService')
                                  ->shouldReceive('send')
                                  ->once()
                                  ->andThrow(new AuthenticationErrorException)
                                  ->shouldReceive('getErrors')
                                  ->getMock();
        $this->app->instance('Jai\Authentication\Services\ReminderService', $mock_reminder_service);

        $this->action('POST', 'Jai\Authentication\Controllers\AuthController@postReminder');

        $this->assertRedirectedToAction('Jai\Authentication\Controllers\AuthController@getReminder');
        $this->assertSessionHasErrors();
    }

    /**
     * @test
     **/
    public function it_change_password_with_success()
    {
        $mock_reminder_service = m::mock('Jai\Authentication\Services\ReminderService')
                                  ->shouldReceive('reset')
                                  ->once()
                                  ->getMock();

        $this->app->instance('Jai\Authentication\Services\ReminderService', $mock_reminder_service);

        $this->action('POST', 'Jai\Authentication\Controllers\AuthController@postChangePassword', ["password" => "newpassword"]);
        $this->assertRedirectedTo('/user/change-password-success');
    }

    /**
     * @test
     **/
    public function it_change_password_with_error()
    {
        $mock_reminder_service = m::mock('Jai\Authentication\Services\ReminderService')
                                  ->shouldReceive('reset')
                                  ->once()
                                  ->andThrow(new AuthenticationErrorException)
                                  ->shouldReceive('getErrors')
                                  ->getMock();
        $this->app->instance('Jai\Authentication\Services\ReminderService', $mock_reminder_service);

        $this->action('POST', 'Jai\Authentication\Controllers\AuthController@postChangePassword', ["password" => "newpassword"]);

        $this->assertRedirectedToAction('Jai\Authentication\Controllers\AuthController@getChangePassword');
        $this->assertSessionHasErrors();
    }

    /**
     * @test
     **/
    public function itValidatePasswordOnChangePassword()
    {
        $this->action('POST', 'Jai\Authentication\Controllers\AuthController@postChangePassword', ["password" => ""]);

        $this->assertRedirectedToAction('Jai\Authentication\Controllers\AuthController@getChangePassword');
        $this->assertSessionHasErrors();
        $this->assertSessionHas("_old_input");
    }
}
 