<?php
class Config extends BaseConfig
{
  public function __construct()
  {
    parent::__construct();
    echo 'Hello from Config class<br>';
  }

  public static function getInstance()
  {
    echo 'Running getInstance()<br>';
  }
}