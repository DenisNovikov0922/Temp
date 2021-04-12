<?php
class Validation_AlphaNumeric extends Validation_RegExp {
	protected $message="";

	public function __construct($message = "") {
                if(empty($message))
                    $this->message= __('Error: %element% must be alphanumeric (contain only numbers, letters, underscores, and/or hyphens).','registrationmagic-gold');
		parent::__construct("/^[a-zA-Z0-9_-]+$/", $message);
	}
}
