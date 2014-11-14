<?php  namespace Jai\Authentication\Validators; 

use Jai\Library\Validators\AbstractValidator;

class UserProfileUserValidator extends AbstractValidator{
    protected static $rules = array(
            "password" => ["confirmed", "min:6"],
    );
} 