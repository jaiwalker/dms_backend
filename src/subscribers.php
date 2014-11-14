<?php
/*
|--------------------------------------------------------------------------
| Editable subscriber
|--------------------------------------------------------------------------
|
| Check if the current record is editable
|
*/
use Jai\Authentication\Events\EditableSubscriber;
Event::subscribe(new EditableSubscriber());
/*
|--------------------------------------------------------------------------
| Profile type permissions subscriber
|--------------------------------------------------------------------------
|
| Check if the current use can edit the Profile permission types
|
*/
use Jai\Authentication\Classes\CustomProfile\Events\ProfilePermissionSubscriber;
Event::subscribe(new ProfilePermissionSubscriber());
