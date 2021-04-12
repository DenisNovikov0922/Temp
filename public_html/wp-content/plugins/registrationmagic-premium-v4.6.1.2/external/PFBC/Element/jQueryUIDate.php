<?php
class Element_jQueryUIDate extends Element_Textbox {
	protected $_attributes = array(
		"type" => "text",
		"autocomplete" => "off",
                "readonly" => true
                /*"pattern" => "^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$"*/
	);
	protected $jQueryOptions="";
        
        public function __construct($label, $name, array $properties = null)
        {
            parent::__construct($label, $name, $properties);
            
            if(!isset($properties['date_format']) || !$properties['date_format'])
                $this->_attributes['data-dateformat'] = 'mm/dd/yy';
            else
                $this->_attributes['data-dateformat'] = $properties['date_format'];
        }

	public function getCSSFiles() {
		return array(
			"https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.min.css"
		);
	}

	public function getJSFiles() {
		return array(
			//$this->_form->getPrefix() . "://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"
		);
	}

   public function jQueryDocumentReady($init=true) {
        
        parent::jQueryDocumentReady();
        if($init==true) {
            $id = $this->_attributes["id"];//var_dump($id);
            $dateformat = $this->_attributes["data-dateformat"];
$dpjs = <<<JSDP
        jQuery("#{$id}").datepicker({
                                        dateFormat:"{$dateformat}",
                                        changeMonth:true,
                                        changeYear:true,
                                        yearRange: '1900:+50',
                                        beforeShow: function(input, inst){
                                                        if(inst.id === "{$id}") {
                                                            jQuery("#ui-datepicker-div").addClass("rm_jqui_element");
                                                        } else {
                                                            jQuery("#ui-datepicker-div").removeClass("rm_jqui_element");
                                                        }
                                                    }
                                    });
JSDP;
            echo $dpjs;
        }
    }

    public function render() {
        $this->validation[] = new Validation_Date;
        
        parent::render();
    }
}
