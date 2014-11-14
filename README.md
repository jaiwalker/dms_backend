laravel-authentication-acl    DMS BACKEND
==========================

NOTE : If you are editing this - just check the composer.json file

# Steps on how to install  this for your workbench along with creating a new workbench package :

   Install laravel using  composer  or laravel specific installation
   Set you your local ENV
   For Building  a new package ;
   Run this command -  $php artisan workbench Jai/new Package name

   # TO Install ACL:
   On command line navigate to workbench/jai
   run this  : git clone  https://github.com/jaiwalker/dms_backend.git laravel-authentication-acl
   also run this  git clone  https://github.com/jaiwalker/dms_backend_lib.git  laravel-library
   this should create 2 new folders in jai folder

   Go to file app/config/app.php add to the 'providers'
   option the following line: 'Jai\Authentication\AuthenticationServiceProvider',

   jai/
   --- -/laravel-library
   --- -/laravel-authentication-acl

   Now navigate into folder laravel-authentication-acl/   and run composer-dump  autoload
   this should load all composer dependencies to vendor folder.
   Do the same for laravel-library/ folder

   Now publish assets out : php artisan asset:publish --bench="jai/laravel-authentication-acl"

   Now config Migrations :
    Note: make sure that db Config is specified in you ENV folder
    go back to root folder i.e  new Package name/
    Run :   php artisan authentication:install.   this should do all your migrations and seed database





# Recent Change Log :
 # remove Blog dependencies and moved them  to blog specific package

=======
NOTE : If you are editing this - just check the composer.json file 


Laravel Authentication ACL is a Laravel 4 package, based on <a href="https://github.com/cartalyst/sentry" target="_blank">sentry2</a>. <br/>
This package is made with the purpose of helping other developers to set-up
a fully featured admin panel with an ACL using Laravel framework.

You can see the full documentation and usage [here](docs/index.md)

####Main features:
 - User authentication and signup
 - Configurable email confirmation
 - Configurable captcha integration
 - Can create groups and permissions and associate permissions to user or group
 - Any user can have multiple groups and permissions
 - Login throttling and password recovery
 - Password strength check
 - User banning
 - Dashboard
 - Infinite custom profile fields!
 - User custom avatar and gravatar support
 - Allow connection to a custom database other then laravel default
 - Create custom menu items with configurable permissions
 - Can handle permission on custom user routes
 - Have two login forms: admin area and user area
 - Many usable hasing algorithms sha256, md5 etc...
 - Laravel4 based and easy to integrate in any Laravel application
 - Have an Api that integrates with your application
 - Bootstrap 3 and responsive design (mobile first)
 - Easy install script from command line
 - Fully customizable and easy to extend
 - Works with major DBMS (mysql, sqlite, postgres)
 - 100% object oriented
 - The code is fully tested with Phpunit

####Interested in some new feature?
There's something you like to see in this package?
Contact me and i'll do my best to implement that in next releases.