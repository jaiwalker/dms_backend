<?php  namespace Jai\Authentication\Models;
/**
 * Class Group
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
use Cartalyst\Sentry\Groups\Eloquent\Group as SentryGroup;
use Jai\Library\Traits\OverrideConnectionTrait;

class Group extends SentryGroup
{
    use OverrideConnectionTrait;

    protected $guarded = ["id"];

    protected $fillable = ["name", "permissions", "protected"];
} 