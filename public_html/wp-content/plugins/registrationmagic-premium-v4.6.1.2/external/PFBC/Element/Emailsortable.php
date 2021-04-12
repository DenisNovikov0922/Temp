<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Emailsortable
 *
 * @author CMSHelplive
 */
class Element_Emailsortable extends Element_Textboxsortable
{
   protected $_attributes = array("type" => "email");
	
	 public function __construct($label, $name, array $properties = null, array $others = array()) {
            parent::__construct($label, $name, $properties, $others);
            $this->validation[] = new Validation_Email;
        }
	
	public function render() {
		parent::render();
	}
}
