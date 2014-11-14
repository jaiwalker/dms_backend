<?php
// mail validator
Validator::extend('mail_signup', 'Jai\Authentication\Validators\UserSignupEmailValidator@validateEmailUnique');
// captcha validator
use Jai\Authentication\Classes\Captcha\GregWarCaptchaValidator;
$captcha_validator = App::make('captcha_validator');
Validator::extend('captcha', 'Jai\Authentication\Classes\Captcha\GregWarCaptchaValidator@validateCaptcha', $captcha_validator->getErrorMessage() );