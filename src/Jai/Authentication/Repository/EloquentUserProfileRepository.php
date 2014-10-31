<?php  namespace Jai\Authentication\Repository;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jai\Authentication\Classes\Images\ImageHelperTrait;
use Jai\Authentication\Exceptions\UserNotFoundException;
use Jai\Authentication\Exceptions\ProfileNotFoundException;
use Jai\Authentication\Models\User;
use Jai\Authentication\Models\UserProfile;
use Jai\Authentication\Repository\Interfaces\UserProfileRepositoryInterface;
use Jai\Library\Repository\EloquentBaseRepository;
use Jai\Library\Repository\Interfaces\BaseRepositoryInterface;

/**
 * Class EloquentUserProfileRepository
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
class EloquentUserProfileRepository extends EloquentBaseRepository implements UserProfileRepositoryInterface
{
    use ImageHelperTrait;

    /**
     * We use the user profile as a model
     */
    public function __construct()
    {
        return parent::__construct(new UserProfile);
    }

    public function getFromUserId($user_id)
    {
        // checks if the user exists
        try {
            User::findOrFail($user_id);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException;
        }
        // gets the profile
        $profile = $this->model->where('user_id', '=', $user_id)
            ->get();

        // check if the profile exists
        if ($profile->isEmpty()) throw new ProfileNotFoundException;

        return $profile->first();
    }

    public function updateAvatar($id, $input_name = "avatar")
    {
        $model = $this->find($id);
        $model->update([
            "avatar" => static::getBinaryData('170', $input_name)
        ]);
    }

    public function attachEmptyProfile($user)
    {
        if($this->hasAlreadyAnUserProfile($user)) return;

        return $this->create(["user_id" => $user->id]);
    }

    /**
     * @param $user
     * @return mixed
     */
    protected function hasAlreadyAnUserProfile($user) {
        return $user->user_profile()->count();
    }
}