<?php  namespace Jai\Authentication\Controllers;
/**
 * Class GroupController
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
use Illuminate\Support\MessageBag;
use Jai\Authentication\Presenters\GroupPresenter;
use Jai\Library\Form\FormModel;
use Jai\Authentication\Helpers\FormHelper;
use Jai\Authentication\Models\Group;
use Jai\Authentication\Exceptions\UserNotFoundException;
use Jai\Authentication\Validators\GroupValidator;
use Jai\Library\Exceptions\JaiExceptionsInterface;
use View, Input, Redirect, App;

class GroupController extends \Controller
{
    /**
     * @var \Jai\Authentication\Repository\SentryGroupRepository
     */
    protected $group_repository;
    /**
     * @var \Jai\Authentication\Validators\GroupValidator
     */
    protected $group_validator;
    /**
     * @var FormHelper
     */
    protected $form_model;

    public function __construct(GroupValidator $v, FormHelper $fh)
    {
        $this->group_repository = App::make('group_repository');
        $this->group_validator = $v;
        $this->f = new FormModel($this->group_validator, $this->group_repository);
        $this->form_model = $fh;
    }

    public function getList()
    {
        $groups = $this->group_repository->all(Input::all());

        return View::make('laravel-authentication-acl::admin.group.list')->with(["groups" => $groups]);
    }

    public function editGroup()
    {
        try
        {
            $obj = $this->group_repository->find(Input::get('id'));
        }
        catch(UserNotFoundException $e)
        {
            $obj = new Group;
        }
        $presenter = new GroupPresenter($obj);

        return View::make('laravel-authentication-acl::admin.group.edit')->with(["group" => $obj, "presenter" => $presenter]);
    }

    public function postEditGroup()
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
            return Redirect::route("users.groups.edit", $id ? ["id" => $id]: [])->withInput()->withErrors($errors);
        }
        return Redirect::action('Jai\Authentication\Controllers\GroupController@editGroup',["id" => $obj->id])->withMessage("Group edited successfully.");
    }

    public function deleteGroup()
    {
        try
        {
            $this->f->delete(Input::all());
        }
        catch(JaiExceptionsInterface $e)
        {
            $errors = $this->f->getErrors();
            return Redirect::action('Jai\Authentication\Controllers\GroupController@getList')->withErrors($errors);
        }
        return Redirect::action('Jai\Authentication\Controllers\GroupController@getList')->withMessage("Group deleted successfully.");
    }

    public function editPermission()
    {
        // prepare input
        $input = Input::all();
        $operation = Input::get('operation');
        $this->form_model->prepareSentryPermissionInput($input, $operation);
        $id = Input::get('id');

        try
        {
            $obj = $this->group_repository->update($id, $input);
        }
        catch(JaiExceptionsInterface $e)
        {
            return Redirect::route("users.groups.edit")->withInput()->withErrors(new MessageBag(["permissions" => "Permission not found"]));
        }
        return Redirect::action('Jai\Authentication\Controllers\GroupController@editGroup',["id" => $obj->id])->withMessage("Permission edited succesfully.");
    }
}