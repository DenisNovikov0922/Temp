<?php

class RM_Chronos_UI_Strings
{
    public static function get($identifier)
    {
        switch($identifier)
        {
            case 'LABEL_TASK_MANAGER':
                return __('Automation','registrationmagic-gold');      
                
            case 'LABEL_NEW_TASK':
                return __('New Task','registrationmagic-gold');      
                
            case 'NO_TASKS_MSG':
                return __('You haven&apos;t created any task for this form yet.','registrationmagic-gold');      
                
            case 'LABEL_RUN_NOW':
                return __('Run Now','registrationmagic-gold');      
                
            case 'LABEL_RM_AUTO_MENU':
                return __('Automation','registrationmagic-gold');      
                
            case 'LABEL_ENABLE':
                return __('Enable','registrationmagic-gold');      
                
            case 'LABEL_DISABLE':
                return __('Disable','registrationmagic-gold');      
                
            case 'CRON_DISABLED_WARNING':
                return sprintf(__('Wordpress cron is disabled. Automatic task execution will not work. <a target="__blank" href="%s">More info.</a>','registrationmagic-gold'),'https://codex.wordpress.org/Editing_wp-config.php#Disable_Cron_and_Cron_Timeout');
                
            case 'LABEL_HELP':
                return __('Help','registrationmagic-gold');   
                
            case 'LABEL_TASKS':
                return __('Automation Tasks','registrationmagic-gold');   
            
            default:
                return __("NO STRING FOUND (rmchrono)", 'registrationmagic-gold');
        }
    }
}
