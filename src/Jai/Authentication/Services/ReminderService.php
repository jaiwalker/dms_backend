<?php namespace Jai\Authentication\Services;

use Illuminate\Support\MessageBag;
use App;
use Jai\Library\Exceptions\MailException;
use Jai\Authentication\Exceptions\UserNotFoundException;
use Jai\Library\Exceptions\InvalidException;
use Jai\Library\Exceptions\JaiExceptionsInterface;
use Jai\Library\Email\MailerInterface;
use Jai\Authentication\Interfaces\AuthenticatorInterface;
/**
 * Class ReminderService
 *
 * Service to send email and error handling
 *
 * @package Auth
 * @author Jai beschi Jai@Jaibeschi.com
 */
class ReminderService {

    /**
     * Class to send email
     *
     * @var MailerInterface
     */
    protected $mailer;
    /**
     * Email body
     *
     * @var string
     */
    protected $body;
    /**
     * Email subject
     */
    protected $subject = "Password recovery request";
    /**
     * Femplate mail file
     *
     * @var string
     */
    protected $template = "laravel-authentication-acl::admin.mail.reminder";
    /**
     * Errors
     *
     * @var \Illuminate\Support\\MessageBag
     */
    protected $errors ;
    /**
     * @var \Jai\Authentication\Interfaces\AuthenticatorInterface
     */
    protected $auth;

    protected static $INVALID_USER_MAIL = 'There is no user associated with this email.';

    public function __construct()
    {
        $this->auth = App::make('authenticator');
        $this->mailer = App::make('jmailer');
        $this->errors = new MessageBag();
    }

    public function send($to)
    {
        // gets reset pwd code
        try
        {
            $token = $this->auth->getToken($to);
        }
        catch(JaiExceptionsInterface $e)
        {
            $this->errors->add('mail', static::$INVALID_USER_MAIL);
            throw new UserNotFoundException;
        }

        $this->prepareResetPasswordLink($token, $to);

        // send email with change password link
        $success = $this->mailer->sendTo($to, $this->body, $this->subject, $this->template);

        if(! $success)
        {
            $this->errors->add('mail', 'There was an error sending the email');
            throw new MailException;
        }
    }

    private function prepareResetPasswordLink($token, $to)
    {
        $this->body = link_to_action("Jai\\Authentication\\Controllers\\AuthController@getChangePassword","Click here to change your password.", ["email"=> $to, "token"=> $token] );
    }

    public function reset($email, $token, $password)
    {
        try
        {
            $user = $this->auth->getUser($email);
        }
        catch(JaiExceptionsInterface $e)
        {
            $this->errors->add('user', static::$INVALID_USER_MAIL);
            throw new UserNotFoundException;
        }

        // Check if the reset password code is valid
        if ($user->checkResetPasswordCode($token))
        {
            // Attempt to reset the user password
            if (! $user->attemptResetPassword($token, $password))
            {
                $this->errors->add('user', 'There was a problem changing the password.');
                throw new InvalidException();
            }
        }
        else
        {
            $this->errors->add('user', 'Confirmation code is not valid.');
            throw new InvalidException();
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

} 