<?php
class Element_Month extends Element_Textbox {
    protected $_attributes = array(
        "type" => "month",
        "pattern" => "\d{4}-\d{2}"
    );

    public function __construct($label, $name, array $properties = null) {
        $this->_attributes["placeholder"] = sprintf(__('YYYY-MM (e.g. %s)','registrationmagic-gold'),date("Y-m"));
        $this->_attributes["title"] = $this->_attributes["placeholder"];

        parent::__construct($label, $name, $properties);
        $this->validation[] = new Validation_RegExp("/" . $this->_attributes["pattern"] . "/", __('Error: The %element% field must match the following date format:  ','registrationmagic-gold') . $this->_attributes["title"]);
    }

    public function render() {
        parent::render();
    }
}
