<?php  namespace Jai\Authentication\Models;

/**
 * Class ProfileType
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
class ProfileField extends BaseModel
{
    protected $table = "profile_field";

    protected $fillable = ["value", "profile_id", "profile_field_type_id"];

    public function profile_field_type()
    {
        return $this->belongsTo('Jai\Authentication\Models\ProfileFieldType','profile_field_type_id');
    }

    public function user_profile()
    {
        return $this->belongsTo('Jai\Authentication\Models\UserProfile','user_profile_id');
    }
} 