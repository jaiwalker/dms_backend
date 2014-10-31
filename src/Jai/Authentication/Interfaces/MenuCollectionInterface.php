<?php  namespace Jai\Authentication\Interfaces;
/**
 * Interface MenuCollectionInterface
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
interface MenuCollectionInterface
{
    /**
     * Obtain all the menu items
     * @return \Jai\Authentication\Classes\MenuItem
     */
    public function getItemList();

    /**
     * Obtain the menu items that the current user can access
     * @return mixed
     */
    public function getItemListAvailable();

} 