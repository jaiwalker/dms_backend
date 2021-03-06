<?php  namespace Jai\Authentication\Tests\Unit;

/**
 * Test UserSignupEmailValidatorTest
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
use App, Input, Session, Config, Event;
use Jai\Authentication\Tests\Unit\Traits\Helper;
use Jai\Authentication\Validators\UserSignupEmailValidator;
use Mockery as m;
class UserSignupEmailValidatorTest extends DbTestCase {
    use Helper;

    protected $user_repository;

    public function setUp()
    {
        parent::setUp();
        $this->user_repository = App::make('user_repository');
    }

    public function tearDown()
    {
        m::close();
    }
    /**
     * @test
     **/
    public function it_check_if_email_already_exists_and_user_is_active()
    {
        $fake_mail = "email@email.com";
        $input = [
            "email" => $fake_mail,
            "password" => "pass",
            "activated" => 1
        ];
        $this->user_repository->create($input);
        $validator = new UserSignupEmailValidator();
        $this->assertFalse($validator->validateEmailUnique("email", $fake_mail, $input));

        $fake_mail = "email2@email.com";
        $input = [
            "email" => $fake_mail,
            "password" => "pass",
            "activated" => 0
        ];
        $validator = new UserSignupEmailValidator();
        $this->assertTrue($validator->validateEmailUnique("email", $fake_mail, $input));
    }
    
    /**
     * @test
     **/
    public function it_send_email_if_user_exists_and_is_not_active_and_set_message()
    {
        $this->enableEmailConfirmation();

        $input = [
            "email" => "fake@email.com",
            "password" => "pass",
            "activated" => 0,
            "first_name" => "",
            "last_name" => ""
        ];

        StateKeeper::set('expected_to', $input["email"]);
        StateKeeper::set('expected_subject',"Registration request to: " . Config::get('laravel-authentication-acl::app_name'));
        StateKeeper::set('expected_body', 'You account has been created. However, before you can use it you need to confirm your email address first by clicking the');
        Input::shouldReceive('all')->once()->andReturn($input)
                ->shouldReceive('getScheme')
                ->shouldReceive('root');
        $this->activateSingleEmailCheck();

        $this->user_repository->create($input);
        $validator = new UserSignupEmailValidator();

        $this->assertFalse($validator->validateEmailUnique("email", $input["email"], $input));
        $this->assertTrue(Session::has('message'));
    }

    private function enableEmailConfirmation()
    {
        Config::set('laravel-authentication-acl::email_confirmation', true);
    }

    public function activateSingleEmailCheck()
    {
        Event::listen('mailer.sending', 'Jai\Authentication\Tests\Unit\UserSignupEmailValidatorTest@checkForSingleMailData');
    }
}
 