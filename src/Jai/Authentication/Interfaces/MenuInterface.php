<?php namespace Jai\Authentication\Interfaces;
/**
 * Interface MenuIterface
 *
 * @author jai beschi jai@Jaibeschi.com
 */
interface MenuInterface
{
    /**
     * Check if the current user have access to the menu item
     * @return boolean
     */
    public function havePermission();

    /**
     * Obtain the menu link
     * @return mixed
     */
    public function getLink();

    /**
     * Obtain the menu name
     * @return mixed
     */
    public function getName();
}