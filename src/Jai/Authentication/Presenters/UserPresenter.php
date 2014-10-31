<?php  namespace Jai\Authentication\Presenters;
/**
 * Class UserPresenter
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
use Jai\Authentication\Presenters\Traits\PermissionTrait;
use Jai\Library\Presenters\AbstractPresenter;

class UserPresenter extends AbstractPresenter
{
    use PermissionTrait;
    
  
    public function gravatar($size = 30)
    {
        return "http://www.gravatar.com/avatar/" . md5( strtolower( trim( "fasdfasdf" ) ) ) .  "?s=" . $size;

    }
} 