<?php

class RM_LMS_UI_Strings
{
    public static function get($identifier)
    {
        switch($identifier)
        {
            case 'LABEL_RM_GLOBAL_SETTING_MENU':
                return __('LeadMagic','registrationmagic-gold');      
                
            case 'SUBTITLE_RM_GLOBAL_SETTING_MENU':
                return __('Create Landing/ Squeeze pages with your forms','registrationmagic-gold'); 
            
            default:
                return __("NO STRING FOUND (rmlms)", 'registrationmagic-gold');
        }
    }
}

