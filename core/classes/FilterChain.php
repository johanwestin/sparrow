<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class FilterChain
{
  private $chains;
  private $objectChain; 
  
  public function __construct()
  {
    $this->loadChains();
    $this->traverseChain();
  }
  
  private function traverseChain() 
  {
    foreach($this->objectChain as &$filter)
    {
      $filter->execute(); 
    }
  }

  private function loadChains()
  {
    global $registery;
    $this->chains = $registery->FilterChain;

    $sparrow = Sparrow::getInstance();
    foreach($this->chains as $filter)
    {
      $this->objectChain[$filter] = $sparrow->load($filter);
    }
  }
}
?>
