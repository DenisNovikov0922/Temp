<?php
class Element_Url extends Element_Textbox {
	protected $_attributes = array("type" => "url");
	
	public function __construct($label, $name, array $properties = null) {
            parent::__construct($label, $name, $properties);
            $this->validation[] = new Validation_Url;
        }
	
	public function render() {
		parent::render();
	}
}
