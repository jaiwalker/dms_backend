laravel-authentication-acl    DMS BACKEND
==========================

NOTE : If you are editing this - just check the composer.json file
 Acl  wokring DOC https://medium.com/laravel-4/laravel-4-acl-a7f2fa1f9791
# Setps to create a new laravel  package 
   
   ->Command Line  your sites Dir  "composer create-project laravel/laravel your-project-name "
      This should Install laravel  in the project name specified 
  ->Now Create a database table   and config to respective DB  file If  you have set up  an Env make sure  that the DB config is in the right folder. 


# Steps on how to install  this for your workbench along with creating a new workbench package :

   Install laravel using  composer  or laravel specific installation
   Set you your local ENV
   For Building  a new package ;
    -  go  to  config/workbench.php  
        name ='jai' email = 'your choice of email '
   Run this command -   php artisan workbench Jai/new_Package_name

   # TO Install ACL:
   
   On command line navigate to workbench/jai

   run this  : git clone  https://github.com/jaiwalker/dms_backend.git laravel-authentication-acl
               git clone  https://github.com/jaiwalker/dms_backend_lib.git  laravel-library

   this should create 2 new folders in jai folder  

      jai/
      --- -/laravel-library
      --- -/laravel-authentication-acl

   Now Go to file app/config/app.php add to the 'providers'
    add the following in the array :

    'Jai\Authentication\AuthenticationServiceProvider',

  
   Now navigate into folder workbench/jai/laravel-authentication-acl/   
     run "composer update"  and "composer dump-autoload" 
           
   this should load all composer dependencies to vendor folder.
   Do the same for workbench/jai/laravel-library/ folder 
   run  "composer update"   and "composer dump-autoload" 
         
  New Feature added now ( Artisan Command  files ) : http://ryantablada.com/post/creating-an-installer-script

   php artisan authentication:prepare  - this  will publish the config to app/packages/jai/laravel-authentication-acl folder 

   php artisan authentication:install -  this will do all migrations , DB seeding and assests 

 Now   ready to go :    laravel/admin - 
 
    http://url_of_your_application/login the client login page (after logging in will redirect you to the home page) [ username:admin@admin.com password:password ]
    http://url_of_your_application/admin/login the admin login page (after logging in will redirect you to the admin panel) [ username:admin@admin.com password:password ]
    http://url_of_your_application/user/signup the new user signup form (to register a new user)
    http://url_of_your_application/user/logout the logout page


###### OLD WAY OF PUBLISHING THINGS ##########

   Now publish assets out :
    
    Command Line : navigate to main forlder ( project name) and run 
    php artisan asset:publish --bench="jai/laravel-authentication-acl"

    will create an assets folders in pulic folders - puclic/packages/jai/laravel-authentication-acl  

   Now config Migrations :

    Note: make sure that db Config is specified in you ENV folder
    
    go back to root folder i.e  Package name/

    php artisan config:publish --path="workbench/jai/laravel-authentication-acl/src/config" jai/laravel-authentication-acl
     this  should  puclish the workbech package config  to  project folder app foler.

     # Migrations 

     php artisan migrate --bench="vendor/package"  this will do   all the migrations for us

     # DB seeds  

    



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