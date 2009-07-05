<?php
class Config
{
  public function __construct()
  {
    echo 'Hello from Config class<br>';
  }

  public static function getInstance()
  {
    echo 'Running getInstance()<br>';
  }
}