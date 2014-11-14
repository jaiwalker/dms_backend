<?php  namespace Jai\Authentication\Validators; 
/**
 * Class UserSignupEmailValidator
 *
 * @author jai beschi jai@Jaibeschi.com
 */
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\MessageBag;
use Jai\Authentication\Exceptions\UserNotFoundException;
use App, Session, Input, Config;
use Jai\Library\Validators\OverrideConnectionValidator;

class UserSignupEmailValidator extends OverrideConnectionValidator
{
    public function validateEmailUnique($attribute, $value, $parameters)
    {
        $repository = App::make('user_repository');
        try
        {
            $user = $repository->findByLogin($value);
        }
        catch(UserNotFoundException $e)
        {
            return true;
        }

        if($user->activated)
        {
            return false;
        }

        // if email confirmation is disabled we dont send email again
        if(! Config::get('laravel-authentication-acl::email_confirmation') ) return false;

        // send email
        $this->resendConfirmationEmail($value);
        // set session message
        Session::flash('message', "We sent you again the mail confirmation. Please check your inbox.");
        return false;
    }

    /**
     */
    protected function resendConfirmationEmail()
    {
        $data = Input::all();
        $data['password'] = 'Cannot decipher password, please use password recovery after if it\'s needed.';

        App::make('register_service')->sendRegistrationMailToClient($data);
    }
} 