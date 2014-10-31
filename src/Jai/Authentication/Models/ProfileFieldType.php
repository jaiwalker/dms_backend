<?php  namespace Jai\Authentication\Models;

/**
 * Class ProfileTypeField
 *
 * @author jai beschi jai@Jaibeschi.com
 */
class ProfileFieldType extends BaseModel
{
    protected $table = "profile_field_type";

    protected $fillable = ["description"];

    public function profile_field()
    {
        return $this->hasMany('Jai\Authentication\Models\ProfileField');
    }
} 