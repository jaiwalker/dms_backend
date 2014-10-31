<?php  namespace Jai\Authentication\Services;

use Config;
use DB;
use Event;
use Illuminate\Support\Facades\App;
use Illuminate\Support\MessageBag;
use Jai\Authentication\Exceptions\TokenMismatchException;
use Jai\Authentication\Exceptions\UserExistsException;
use Jai\Authentication\Exceptions\UserNotFoundException;
use Jai\Authentication\Helpers\DbHelper;
use Jai\Authentication\Validators\UserSignupValidator;
use Jai\Library\Exceptions\ValidationException;
use Redirect;

/**
 * Class UserRegisterService
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
class UserRegisterService
{
    /**
     * @var \Jai\Authentication\Repository\Interfaces\UserRepositoryInterface
     */
    protected $user_repository;
    /**
     * @var \Jai\Authentication\Repository\Interfaces\UserProfileRepositoryInterface
     */
    protected $profile_repository;
    /**
     * @var \Jai\Authentication\Validators\UserSignupValidator
     */
    protected $user_signup_validator;
    /**
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;
    /**
     * If email activation is enabled
     *
     * @var boolean
     */
    protected $activation_enabled;

    public function __construct(UserSignupValidator $v = null)
    {
        $this->user_repository = App::make('user_repository');
        $this->profile_repository = App::make('profile_repository');
        $this->user_signup_validator = $v ? $v : new UserSignupValidator;
        $this->activation_enabled = Config::get('laravel-authentication-acl::email_confirmation');
        Event::listen('service.activated',
                      'Jai\Authentication\Services\UserRegisterService@sendActivationEmailToClient');
    }


    /**
     * @param array $input
     * @return mixed
     */
    public function register(array $input)
    {
        Event::fire('service.registering', [$input]);
        $this->validateInput($input);

        $input['activated'] = $this->getDefaultActivatedState();
        $user = $this->saveDbData($input);

        $this->sendRegistrationMailToClient($input);

        Event::fire('service.registered', [$input, $user]);

        return $user;
    }

    /**
     * @param array $input
     * @throws \Jai\Library\Exceptions\ValidationException
     */
    protected function validateInput(array $input)
    {
        if(!$this->user_signup_validator->validate($input))
        {
            $this->errors = $this->user_signup_validator->getErrors();
            throw new ValidationException;
        }
    }

    /**
     * @param array $input
     * @return mixed $user
     */
    protected function saveDbData(array $input)
    {
        DbHelper::startTransaction();
        try
        {
            $user = $this->user_repository->create($input);
            $this->profile_repository->create($this->createProfileInput($input, $user));
        } catch(UserExistsException $e)
        {
            DbHelper::rollback();
            $this->errors = new MessageBag(["model" => "User already exists."]);
            throw new UserExistsException;
        }
        DbHelper::commit();

        return $user;
    }

    protected function getDefaultActivatedState()
    {
        return Config::get('laravel-authentication-acl::email_confirmation') ? false : true;
    }

    /**
     * @param $mailer
     * @param $user
     */
    public function sendRegistrationMailToClient($input)
    {
        $view_file = $this->activation_enabled ? "laravel-authentication-acl::admin.mail.registration-waiting-client" : "laravel-authentication-acl::admin.mail.registration-confirmed-client";

        $mailer = App::make('jmailer');

        // send email to client
        $mailer->sendTo($input['email'], [
                                               "email"      => $input["email"],
                                               "password"   => $input["password"],
                                               "first_name" => $input["first_name"],
                                               "token"      => $this->activation_enabled ? App::make('authenticator')->getActivationToken($input["email"]) : ''
                                       ],
                        "Registration request to: " . Config::get('laravel-authentication-acl::app_name'),
                        $view_file);
    }


    /**
     * Send activation email to the client if it's getting activated
     *
     * @param $obj
     */
    public function sendActivationEmailToClient($user)
    {
        $mailer = App::make('jmailer');
        // if i activate a deactivated user
        $mailer->sendTo($user->email, ["email" => $user->email],
                        "Your user is activated on: " . Config::get('laravel-authentication-acl::app_name'),
                        "laravel-authentication-acl::admin.mail.registration-activated-client");
    }

    /**
     * @param $email
     * @param $token
     * @throws \Jai\Authentication\Exceptions\UserNotFoundException
     * @throws \Jai\Authentication\Exceptions\TokenMismatchException
     */
    public function checkUserActivationCode($email, $token)
    {
        $token_msg = "The given token/email are invalid.";

        try
        {
            $user = $this->user_repository->findByLogin($email);
        } catch(UserNotFoundException $e)
        {
            $this->errors = new MessageBag(["token" => $token_msg]);
            throw new UserNotFoundException;
        }
        if($user->activation_code != $token)
        {
            $this->errors = new MessageBag(["token" => $token_msg]);
            throw new TokenMismatchException;
        }

        $this->user_repository->activate($email);
        Event::fire('service.activated', $user);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function getToken()
    {
        return csrf_token();
    }

    /**
     * @param array $input
     * @param       $user
     * @return array
     */
    private function createProfileInput(array $input, $user)
    {
        return array_merge(["user_id" => $user->id],
                           array_except($input, ["email", "password", "activated"]));
    }
} 