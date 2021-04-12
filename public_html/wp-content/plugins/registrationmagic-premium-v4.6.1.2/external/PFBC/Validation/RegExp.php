<?php
class Validation_RegExp extends Validation {
	protected $message = null;
	protected $pattern;

	public function __construct($pattern, $message = "") {
		if(!empty($message))
			$this->message = $message;
		else
			$this->message = RM_UI_Strings::get('FORM_ERR_INVALID_REGEX');

		$this->pattern = $pattern;
		parent::__construct($this->message);
	}

	public function isValid($value) {
		if($this->isNotApplicable($value) || preg_match($this->pattern, $value))
			return true;
		return false;	
	}
}
