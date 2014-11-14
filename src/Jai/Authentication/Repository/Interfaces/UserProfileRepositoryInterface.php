<?php  namespace Jai\Authentication\Repository\Interfaces;
/**
 * Interface UserProfileRepositoryInterface
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
interface UserProfileRepositoryInterface
{
    /**
     * Obtains the profile from the user_id
     * @param $user_id
     * @return mixed
     */
    public function getFromUserId($user_id);
}