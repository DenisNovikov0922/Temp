<?php
/**
 * Centralized email related database operations
 * Main email table used is sent_emails and notes.
 * To acoid confusion with method/prop names, 'sent' and 'received' keywords are from "By ADMIN" perspective only.
 * That is sent email means sent by admin and received means received by admin. 
 */

class RM_Email_Repository {
    
    public function get_email_count($resp_email, $include_type = null) {      
        if(!$include_type || !is_array($include_type) || count($include_type) == 0) {
            return RM_DBManager::count('SENT_EMAILS', array('to' => $resp_email), array('%s'));
        } else {
            $types = "'".implode("','",$include_type)."'";
            $res = RM_DBManager::get_generic('SENT_EMAILS', 'COUNT(DISTINCT #UID#) as mails', "`to` = '$resp_email' AND `type` in ($types)");
        if(is_array($res) && isset($res[0]))
            return (int) $res[0]->mails;
        else
            return 0;
       }
    }
    public function get_email_unread_count($resp_email, $include_type = null) {      
        if(!$include_type || !is_array($include_type) || count($include_type) == 0) {
            return RM_DBManager::count('SENT_EMAILS', array('to' => $resp_email, 'is_read_by_user'=>0), array('%s'));
        } else {
            $types = "'".implode("','",$include_type)."'";
            $res = RM_DBManager::get_generic('SENT_EMAILS', 'COUNT(DISTINCT #UID#) as mails', "`to` = '$resp_email' AND `is_read_by_user` = 0 AND `type` in ($types)");
        if(is_array($res) && isset($res[0]))
            return (int) $res[0]->mails;
        else
            return 0;
       }
    }
    
    public function get_emails($resp_email, $include_type = null, $limit, $offset) {
      
       if(!$include_type || !is_array($include_type) || count($include_type) == 0) {
            return RM_DBManager::get('SENT_EMAILS', array('to' => $resp_email), array('%s'), 'results', $offset, $limit);
       } else {
            $types = "'".implode("','",$include_type)."'";
            return RM_DBManager::get_generic('SENT_EMAILS', '*', "`to` = '$resp_email' AND `type` in ($types) ORDER BY #UID# DESC LIMIT $limit OFFSET $offset");
       }
    }
    
    public function mark_email_read($email_id) {
        global $wpdb;
        $table_name = RM_Table_Tech::get_table_name_for('SENT_EMAILS');
        $wpdb->update($table_name, array('is_read_by_user' => 1), array('mail_id' => $email_id), array('%d'), array('%d'));
    }
}
