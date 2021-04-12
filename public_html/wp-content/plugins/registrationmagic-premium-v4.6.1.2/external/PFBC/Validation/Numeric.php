<?php
class Validation_Numeric extends Validation {
	protected $message;

	public function __construct($message = "") {
		//if(!empty($message))
		$this->message = RM_UI_Strings::get('FORM_ERR_INVALID_NUMBER');
	}

	public function isValid($value) {
		if($this->isNotApplicable($value) || is_numeric($value))
			return true;
		return false;	
	}
}
