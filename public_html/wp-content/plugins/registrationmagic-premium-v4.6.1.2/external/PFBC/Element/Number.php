<?php
class Element_Number extends Element_Textbox {
	protected $_attributes = array("type" => "number");
	
	public function __construct($label, $name, array $properties = null) {
            parent::__construct($label, $name, $properties);
            $this->validation[] = new Validation_Numeric;
            
        }
	
	public function render() {
		parent::render();
	}
}
