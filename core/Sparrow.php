<?php
/* Sparrow.php
 *
 * @author Carl-Johan Westin
 * @license GPL
 */
set_include_path(get_include_path() . ':' . dirname(__FILE__) . DIRECTORY_SEPARATOR . 'libs' );

require_once('Zend/Registry.php');
require_once('yaml/lib/sfYamlParser.php');

class Sparrow
{
  private $filterChains;
  private static $_instance;
  
  private function __construct()
  {
    // init registery
    $this->loadConfig();

  }

  public function init()
  {
    $this->filterChains = $this->load('FilterChain');
    // Still empty
  }

  public static function getInstance()
  {
    if(self::$_instance == null)
    {
      self::$_instance = new Sparrow();
    }
    

    return self::$_instance;
  }

  private function loadConfig()
  {
    global $registery;
    $yaml = new sfYamlParser();
    
    $config_files = array_diff( scandir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config') , Array( ".", ".." ) );



    // scandir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config') ;

    foreach($config_files as $file)
    {
      if($file != '.' || $file != '..')
      {
        try
        {
          $config_array[] = $yaml->parse(file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config'. DIRECTORY_SEPARATOR .$file));
        } catch(InvalidArgumentException $e) {
          $this->printError( $e->getMessage() );
        }
      }
    }

    $config = array();
    foreach($config_array as $config_files)
    {
      if(is_array($config_files))
        $config = array_merge($config, $config_files);
    }

    $this->registery = new Zend_Registry($config);
    $registery = $this->registery; 
  }

  public function load($class, $ns = null, $instance_method = null, $params = null)
  {
    if($ns === null)
      $ns = 'core';

    $ns_custom_name = $ns . '_custom';
    $custom_files_to_load = array();
    $files_to_load = array();
    
    if($this->registery->$ns_custom_name)
    {
      $custom_namespace = $this->registery->$ns_custom_name;
      $custom_files_to_load = $custom_namespace[$class]['files'];
      $class_name = $custom_namespace[$class]['name'];
    } else {
      $namespace = $this->registery->$ns;
      $class_name = $namespace[$class]['name'];
    }
    
    $namespace = $this->registery->$ns;
    $files_to_load = $namespace[$class]['files'];

    if(is_array($files_to_load) && is_array($custom_files_to_load))
    {
      $files_to_load = array_merge($files_to_load, $custom_files_to_load);
    }

    return $this->loadClass($files_to_load, $class_name, $instance_method = null, $params = null);
  }

  private function loadClass($files_to_load, $class_name, $instance_method = null, $params = null)
  {
    if(count($files_to_load) == 0)
      return false;
      
    foreach($files_to_load as $file) {
      require_once $file;
    }

    if($instance_method === null)
    {
      $object = new $class_name;
    } else {
      $object = call_user_func(array($class_name, $instance_method), $params);
    }

    return $object;
  }
  private function printError($error)
  {
    echo '<pre>' . $error . '</pre>'; 
  }
}