<?php  namespace Jai\Authentication\Controllers;
/**
 * Class PermissionController
 *
 * @author jai beschi jai@Jaibeschi.com
 */
use Jai\Library\Form\FormModel;
use Jai\Authentication\Models\Permission;
use Jai\Authentication\Validators\PermissionValidator;
use Jai\Library\Exceptions\JaiExceptionsInterface;
use View, Input, Redirect, App;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionController extends \Controller
{
    /**
     * @var \Jai\Authentication\Repository\PermissionGroupRepository
     */
    protected $r;
    /**
     * @var \Jai\Authentication\Validators\PermissionValidator
     */
    protected $v;

    public function __construct(PermissionValidator $v)
    {
        $this->r = App::make('permission_repository');
        $this->v = $v;
        $this->f = new FormModel($this->v, $this->r);
    }

    public function getList()
    {
        $objs = $this->r->all();

        return View::make('laravel-authentication-acl::admin.permission.list')->with(["permissions" => $objs]);
    }

    public function editPermission()
    {
        try
        {
            $obj = $this->r->find(Input::get('id'));
        }
        catch(JaiExceptionsInterface $e)
        {
            $obj = new Permission;
        }

        return View::make('laravel-authentication-acl::admin.permission.edit')->with(["permission" => $obj]);
    }

    public function postEditPermission()
    {
        $id = Input::get('id');

        try
        {
            $obj = $this->f->process(Input::all());
        }
        catch(JaiExceptionsInterface $e)
        {
            $errors = $this->f->getErrors();
            // passing the id incase fails editing an already existing item
            return Redirect::route("permission.edit", $id ? ["id" => $id]: [])->withInput()->withErrors($errors);
        }

        return Redirect::action('Jai\Authentication\Controllers\PermissionController@editPermission',["id" => $obj->id])->withMessage("Permission edited with success.");
    }

    public function deletePermission()
    {
        try
        {
            $this->f->delete(Input::all());
        }
        catch(JaiExceptionsInterface $e)
        {
            $errors = $this->f->getErrors();
            return Redirect::action('Jai\Authentication\Controllers\PermissionController@getList')->withErrors($errors);
        }
        return Redirect::action('Jai\Authentication\Controllers\PermissionController@getList')->withMessage("Permission deleted with success.");
    }
} 