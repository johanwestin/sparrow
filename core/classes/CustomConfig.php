<?php
class CustomConfig extends Config
{
  public function __construct()
  {
    parent::__construct(); 
    echo 'Hello from Custom Config class<br>';
  }

  public static function getInstance()
  {
    parent::getInstance(); 
    echo 'Running Custom getInstance()<br>';
  }
}
