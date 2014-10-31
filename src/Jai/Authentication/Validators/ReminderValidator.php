<?php namespace Jai\Authentication\Validators;

use Jai\Library\Validators\OverrideConnectionValidator;

class ReminderValidator extends OverrideConnectionValidator
{
    protected static $rules = array(
        "password" => ["required", "min:6"],
    );
}