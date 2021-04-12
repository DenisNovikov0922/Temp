<?php

/**
 * Repository of all the string resources used in Dropbox integration
 * for easy translation and management. 
 *
 */

class RM_Dpx_UI_Strings
{
    public static function get($identifier)
    {
        switch($identifier)
        {
            
            case 'LABEL_DPX_ACCESS_TOKEN':
                return __('Dropbox App Access Token','registrationmagic-gold');
                
            case 'LABEL_ENABLE_DPX':
                return __('Upload submission PDF to Dropbox','registrationmagic-gold');
             
            case 'HELP_OPTIONS_DPX':
                return sprintf(__('Enables Dropbox integration. Submission PDF will be uploaded in corresponding Form folder. Make sure Dropbox App Token is configured in \'External Integrations\'(Global Settings). '."<a target='_blank' class='rm-more' href='%s'>More</a>",'registrationmagic-gold'),'https://registrationmagic.com/knowledgebase/dropbox-integration/#htuploadpdf');    
                
            case 'HELP_OPTIONS_DPX_TOKEN':
                return sprintf(__("Dropbox uses an App Token for authentication. Token can be generated after creating an App. For more details <a target='_blank' href='%s'>Click Here</a>. Once token is configured, you can enable integration from individual form's Form Dashboard --> Integrate --> Dropbox section. Once token configured you can enable integration from individual form's <b>Post Submission</b> Settings. <a target='_blank' class='rm-more' href='%s'>More</a>",'registrationmagic-gold'),'https://www.dropbox.com/developers/reference/oauth-guide','https://registrationmagic.com/knowledgebase/external-integrations/#htenabledropbox'); 
            
            case 'LABEL_F_DPX_SETT':
                return __('Dropbox','registrationmagic-gold');
            
            case 'LABEL_DPX_CLIENT_ID':
                return __("Dropbox Client Key", 'registrationmagic-gold');
                
            case 'LABEL_DPX_CLIENT_SECRET':
                return __("Dropbox Client Secret", 'registrationmagic-gold');
            
            case 'HELP_OPTIONS_DPX_CLIENT_ID':
                return sprintf(__('Enter your Dropbox Key to enable Dropbox integration with forms. Key can be generated after creating an App from <a target="__blank" href="%s" rel="nofollow">here</a>. Once the Key, Secret and Access Token are configured, enable Dropbox integration with individual forms from their Form Dashboard --> Integrate --> Dropbox section. <a target="_blank" class="rm-more" href="%s">More</a>', 'registrationmagic-gold'),'https://www.dropbox.com/developers/apps','https://registrationmagic.com/knowledgebase/external-integrations/#htdropboxkey');
                
            case 'HELP_OPTIONS_DPX_CLIENT_SECRET':
                return sprintf(__("Enter your Dropbox Secret to enable Dropbox integration with forms. Secret can be generated after creating an App from <a target='__blank' href='%s' rel='nofollow'>here</a>. Once the Key, Secret and Access Token are configured, enable Dropbox integration with individual forms from their Form Dashboard --> Integrate --> Dropbox section. <a target='_blank' class='rm-more' href='%s'>More</a>", 'registrationmagic-gold'),'https://www.dropbox.com/developers/apps','https://registrationmagic.com/knowledgebase/external-integrations/#htdropboxsecret');
                
            default:
                return __("NO STRING FOUND (rmdpx)", 'registrationmagic-gold');
        }
    }
}