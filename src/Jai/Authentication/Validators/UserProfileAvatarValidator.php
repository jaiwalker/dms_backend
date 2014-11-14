<?php  namespace Jai\Authentication\Validators;

use Jai\Library\Validators\AbstractValidator;

/**
 * Class UserProfileAvatarValidator
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
class UserProfileAvatarValidator extends AbstractValidator
{
    protected static $rules = [
        "avatar" => ['image','required', 'max:4000']
    ];
} 