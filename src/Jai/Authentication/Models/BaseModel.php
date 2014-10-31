<?php  namespace Jai\Authentication\Models;
/**
 * Class BaseModel
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
use Illuminate\Database\Eloquent\Model;
use Jai\Library\Traits\OverrideConnectionTrait;

class BaseModel extends Model
{
    use OverrideConnectionTrait;
} 