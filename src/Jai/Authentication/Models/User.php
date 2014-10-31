<?php namespace Jai\Authentication\Models;
/**
 * Class User
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
use Cartalyst\Sentry\Users\Eloquent\User as CartaUser;
use Jai\Library\Traits\OverrideConnectionTrait;
use Cartalyst\Sentry\Users\UserExistsException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Jai\Authentication\Presenters\UserPresenter;

class User extends CartaUser
{
    use OverrideConnectionTrait;

    protected $fillable = ["email", "password", "permissions", "activated", "activation_code", "activated_at", "last_login", "protected", "banned"];

    protected $guarded = ["id"];

   
    /**
     * Validates the user and throws a number of
     * Exceptions if validation fails.
     *
     * @override
     * @return bool
     * @throws \Cartalyst\Sentry\Users\UserExistsException
     */
    public function validate()
    {
        if ( ! $login = $this->{static::$loginAttribute})
        {
            throw new LoginRequiredException("A login is required for a user, none given.");
        }

        // Check if the user already exists
        $query = $this->newQuery();
        $persistedUser = $query->where($this->getLoginName(), '=', $login)->first();

        if ($persistedUser and $persistedUser->getId() != $this->getId())
        {
            throw new UserExistsException("A user already exists with login [$login], logins must be unique for users.");
        }

        return true;
    }

    public function user_profile()
    {
        return $this->hasMany('Jai\Authentication\Models\UserProfile');
    }
    
     public function presenter()
    {
        return new UserPresenter($this);
    }
    
// NOTE:: to jai --- made a big change - insted of makign a relationShip  of any package in here  you can create a own model  and extend  this pakeg and add method to this   
//    public function comment()
//	{
//		return $this->hasMany('Comment');
//	}
} 