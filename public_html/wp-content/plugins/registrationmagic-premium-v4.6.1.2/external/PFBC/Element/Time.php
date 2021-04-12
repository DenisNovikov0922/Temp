<?php
class Element_Time extends Element_Textbox {
	protected $_attributes = array("type" => "text", "style" => "z-index:999");
        
    protected $jQueryOptions = "";
    
    public function __construct($label, $name, array $properties = null)
    {
        parent::__construct($label, $name, $properties);
        
        if(isset($properties['required']))
        $this->_attributes['required']=$properties['required'];
        $this->_attributes['class']= 'timepicker_'.$name;
    }

     public function getJSFiles()
    { 
        return array(
            'script_rm_time' => RM_BASE_URL . 'admin/js/wickedpicker.min.js' );
    }
   public function getCSSFiles()
    {
        return array(
            'script_rm_time' => RM_BASE_URL . 'admin/js/wickedpicker.min.css' );
    }


    public function jQueryDocumentReady()
    {
        $time = $this->get_time($this->getAttribute('value'));
        if($time)
            $set_time = ",now:'". $time."'";
        else 
            $set_time = '';
        
        parent::jQueryDocumentReady();
        echo "if(jQuery('.timepicker_".$this->_attributes['name']."').length>0){jQuery('.timepicker_".$this->_attributes['name']."').each(function (i){jQuery(this).wickedpicker({clearable: true", $set_time,"});});}";
    }
    
    private function get_time($value){
        $time_hhmm = '';
        if(is_array($value)){
            
            $value['time'] = strtolower($value['time']);
            $time = explode(':', $value['time']);
           
            if(count($time) === 2){
                if(strstr($time[1], 'pm')){
                        
                    $time[1] = str_replace('pm', '', $time[1]);
                    $time[0] = (int)trim($time[0]);
                    $time[0] = ($time[0] + 12) < 24 ? $time[0]+12 : 0;
                }else{ 
                    
                    $time[1] = str_replace('am', '', $time[1]);
                    $time[0] = trim($time[0]);
                }
                 
                $time[1] = trim($time[1]);
                if(strlen($time[1]) == 1)
                    $time[1] = '0'.$time[1];

                if(strlen($time[0]) == 1)
                    $time[0] = '0'.$time[0];
                
                if(strlen($time[0]) == 2 && strlen($time[1]) == 2 && is_numeric($time[0]) && is_numeric($time[1]))
                    $time_hhmm = $time[0].':'.$time[1];
            }
        }
        return $time_hhmm;
    }

    
}
