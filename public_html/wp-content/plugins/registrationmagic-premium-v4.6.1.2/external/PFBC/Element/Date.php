<?php
class Element_Date extends Element_Textbox {
	protected $_attributes = array(
		"type" => "date",
		"pattern" => "\d{4}-\d{2}-\d{2}"
	);

	public function __construct($label, $name, array $properties = null) {
		$this->_attributes["placeholder"] = sprintf(__('YYYY-MM-DD (e.g. %s)','registrationmagic-gold'),date("Y-m-d"));
		$this->_attributes["title"] = $this->_attributes["placeholder"];

		parent::__construct($label, $name, $properties);
		$this->validation[] = new Validation_RegExp("/" . $this->_attributes["pattern"] . "/", __('Error: The %element% field must match the following date format: ','registrationmagic-gold') . $this->_attributes["title"]);
    }

	public function render() {
		parent::render();
	}
}
