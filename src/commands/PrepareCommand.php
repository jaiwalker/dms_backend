<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PrepareCommand extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'authentication:prepare';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Prepare Laravel Authentication ACL package for install.';

  protected $call_wrapper;

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct($call_wrapper = null)
  {
    //$this->info('## running Prepare command ##');
    $this->call_wrapper = $call_wrapper ? $call_wrapper : new CallWrapper($this);
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function fire()
  {
   // $this->call_wrapper->call('config:publish', ['package' => 'Jai/laravel-authentication-acl' ] );
//var_dump('config:publish', array('--path' => "workbench/jai/laravel-authentication-acl/src/config") 'jai/laravel-authentication-acl' ); die();
    $this->call_wrapper->call('config:publish', array('--path' => "workbench/jai/laravel-authentication-acl/src/config",'package' =>'jai/laravel-authentication-acl') );

    $this->info('## Laravel Authentication ACL prepared successfully ##');

  }

}
