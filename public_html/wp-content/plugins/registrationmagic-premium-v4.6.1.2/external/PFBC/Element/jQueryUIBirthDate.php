<?php
class Element_jQueryUIBirthDate extends Element_jQueryUIDate {
	
 
    public function jQueryDocumentReady($init=true) {
        parent::jQueryDocumentReady(false);
        
        if( $this->required_range!=1)
        {
          echo 'jQuery("#', $this->_attributes["id"], '").datepicker({dateFormat:"'.$this->_attributes["data-dateformat"].'",changeMonth:true,changeYear:true,yearRange: \'1900:+50\',maxDate:new Date()});';
         
        }
          else
         echo 'jQuery("#', $this->_attributes["id"], '").datepicker({dateFormat:"'.$this->_attributes["data-dateformat"].'",changeMonth:true,changeYear:true,yearRange: \'1900:+50\',minDate:new Date("', $this->required_min_range, '"),maxDate:new Date("', $this->required_max_range, '")});';
    
        }
       
            
       

   
}
