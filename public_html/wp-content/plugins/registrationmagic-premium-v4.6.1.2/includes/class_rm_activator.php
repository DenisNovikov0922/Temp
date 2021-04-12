<?php

/**
 * Fired during plugin activation
 *
 * @link       http://registration_magic.com
 * @since      3.0.0
 *
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      3.0.0
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/includes
 * @author     CMSHelplive
 */
class RM_Activator
{

    /**
     * Runs all the actions for plugin activation
     *
     * @since    3.0.0
     */
    public static function activate($network_wide)
    {
        RM_Table_Tech::create_tables($network_wide);
        self::setup_submission_page($network_wide);
        self::setup_recovery_page($network_wide);
        self::setup_login_page($network_wide);
        self::first_install_proc();
        self::migrate($network_wide);
        self::setup_login_options($network_wide);
        update_site_option('rm_option_last_activation_time', time());
    }
    
    private static function setup_recovery_page(){
        global $wpdb;
        if (is_multisite() && $network_wide)
        {
            $current_blog = $wpdb->blogid;
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_ids as $blog_id)
            {
                switch_to_blog($blog_id);
                RM_Utilities::create_recovery_page();
                restore_current_blog();
            }            
        } else
        {
            RM_Utilities::create_recovery_page();
        }   
    }
    
    private static function setup_login_options($network_wide){
        global $wpdb;
        if (is_multisite() && $network_wide){
            $current_blog = $wpdb->blogid;
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_ids as $blog_id)
            {
                switch_to_blog($blog_id);
                RM_Utilities::setup_login_form_options();
                restore_current_blog();
            } 
        }
        else
        {
            RM_Utilities::setup_login_form_options();
        }
        
    }
    
    //Create deafult login page while taking care of multisite installtion 
    private static function setup_login_page($network_wide){
        global $wpdb;

        if (is_multisite() && $network_wide)
        {
            $current_blog = $wpdb->blogid;
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_ids as $blog_id)
            {
                switch_to_blog($blog_id);
                RM_Utilities::create_login_page();
                restore_current_blog();
            }            
        } else
        {
            RM_Utilities::create_login_page();
        }
    }
    
    //Create default submission page while taking care of multisite installation.
    private static function setup_submission_page($network_wide)
    {
        global $wpdb;

        if (is_multisite() && $network_wide)
        {
            $current_blog = $wpdb->blogid;
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_ids as $blog_id)
            {
                switch_to_blog($blog_id);
                RM_Utilities::create_submission_page();
                restore_current_blog();
            }            
        } else
        {
            RM_Utilities::create_submission_page();
        }        
    }

    public static function migrate($network_wide)
    {
        global $wpdb;
        
        $existing_rm_db_version = get_site_option('rm_option_db_version', false);
        $existing_crf_db_version = get_option('crf_db_version', false);
        $existing_rm_version = get_site_option('rm_option_rm_version', false);
        
        if(!$existing_rm_db_version && !$existing_crf_db_version)
        {
            //commenting the below line in 3.5.2.2
           // update_site_option('rm_option_db_version', RM_DB_VERSION);
            update_site_option('rm_option_last_update_time', time());
            do_action("rm_migration_finished");
            return 'fresh_installation';        //No need for migration.
        }
        
        if($existing_rm_db_version && floatval($existing_rm_db_version) >= RM_DB_VERSION)
        {           
            do_action("rm_migration_finished");
            return 'already_on_equal_or_better';
        }
        
        self::migrate_crf_to_rm_4_0($network_wide, $existing_rm_db_version, $existing_crf_db_version);
        self::migrate_rm_4_0_to_rm_4_1($network_wide, $existing_rm_db_version);
        self::migrate_rm_4_1_to_rm_4_2($network_wide, $existing_rm_db_version);
        self::migrate_rm_4_2_to_rm_4_4($network_wide, $existing_rm_db_version);
        self::migrate_rm_4_5_to_rm_4_6($network_wide, $existing_rm_db_version);
        self::migrate_rm_4_6_to_rm_4_7($network_wide, $existing_rm_db_version);
        self::migrate_rm_4_7_to_rm_4_8($network_wide, $existing_rm_db_version);
        self::migrate_rm_4_8_to_rm_4_9($network_wide, $existing_rm_db_version);
        self::migrate_rm_5_0_to_rm_5_2($network_wide, $existing_rm_db_version);
        self::migrate_rm_5_2_to_rm_5_3($network_wide, $existing_rm_db_version);
         // Create primary fields for all the forms (version <= 3.8.2.6)
        
        $rm_service= new RM_Services();
        $forms= $rm_service->get_all('FORMS');
        if(!empty($forms)){
            foreach($forms as $form){
                $rm_service= new RM_Services();
                if(!$rm_service->has_primary_fields($form->form_id) && !empty($form->form_type)){
                    $rm_service->add_primary_fields($form->form_id);
                }
            }
        }
        
        update_site_option('rm_option_last_update_time', time());
        update_site_option('rm_option_db_version', RM_DB_VERSION);
        do_action("rm_migration_finished");
    }

    private static function migrate_rm_4_0_to_rm_4_1($network_wide, $existing_rm_db_version)
    {
        if (floatval($existing_rm_db_version) < 4.1)
        {
            global $wpdb;

            if (is_multisite() && $network_wide)
            {

                $current_blog = $wpdb->blogid;

                $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blog_ids as $blog_id)
                {
                    switch_to_blog($blog_id);
                    self::migrate_per_site_rm_4_0_to_rm_4_1();
                    restore_current_blog();
                }
            } else
            {
                self::migrate_per_site_rm_4_0_to_rm_4_1();
            }
        }
    }
    
    private static function migrate_rm_4_1_to_rm_4_2($network_wide, $existing_rm_db_version)
    {
        if (floatval($existing_rm_db_version) < 4.2)
        {
            global $wpdb;
            if (is_multisite() && $network_wide)
            {
                $current_blog = $wpdb->blogid;
                $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blog_ids as $blog_id)
                {
                    switch_to_blog($blog_id);
                    self::migrate_per_site_rm_4_1_to_rm_4_2();
                    restore_current_blog();
                }
                
            } else
            {
                self::migrate_per_site_rm_4_1_to_rm_4_2();
            }
        }
    }
    
    //Not actual migration, it fixes broken database.
    private static function migrate_rm_4_2_to_rm_4_4($network_wide, $existing_rm_db_version)
    {        
        global $wpdb;
        if (is_multisite() && $network_wide)
        {
            $current_blog = $wpdb->blogid;
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_ids as $blog_id)
            {
                switch_to_blog($blog_id);
                self::migrate_per_site_rm_4_2_to_rm_4_4();
                restore_current_blog();
            }
        } else
        {
            self::migrate_per_site_rm_4_2_to_rm_4_4();
        }        
    }
    
    //Not actual migration, it fixes broken database. Basic edition missed sub_id field in stats table.
    private static function migrate_rm_4_5_to_rm_4_6($network_wide, $existing_rm_db_version)
    {        
        global $wpdb;
        if (is_multisite() && $network_wide)
        {
            $current_blog = $wpdb->blogid;
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_ids as $blog_id)
            {
                switch_to_blog($blog_id);
                self::migrate_per_site_rm_4_5_to_rm_4_6();
                restore_current_blog();
            }
        } else
        {
            self::migrate_per_site_rm_4_5_to_rm_4_6();
        }        
    }
    
    //Update ip address field length to incorporate ipv6 address.
    private static function migrate_rm_4_6_to_rm_4_7($network_wide, $existing_rm_db_version)
    {        
        global $wpdb;
        if (floatval($existing_rm_db_version) < 4.7)
        {
            if (is_multisite() && $network_wide)
            {
                $current_blog = $wpdb->blogid;
                $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blog_ids as $blog_id)
                {
                    switch_to_blog($blog_id);
                    self::migrate_per_site_rm_4_6_to_rm_4_7();
                    restore_current_blog();
                }
            } else
            {
                self::migrate_per_site_rm_4_6_to_rm_4_7();
            }        
        }
    }
    
    //Update paypal log table to store billiing detail, payment processor name and extra data
    private static function migrate_rm_4_7_to_rm_4_8($network_wide, $existing_rm_db_version)
    {        
        global $wpdb;
        if (floatval($existing_rm_db_version) < 4.8)
        {
            if (is_multisite() && $network_wide)
            {
                $current_blog = $wpdb->blogid;
                $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blog_ids as $blog_id)
                {
                    switch_to_blog($blog_id);
                    self::migrate_per_site_rm_4_7_to_rm_4_8();
                    restore_current_blog();
                }
            } else
            {
                self::migrate_per_site_rm_4_7_to_rm_4_8();
            }        
        }
    }
    
    //Update sent_emails table to store user read status and mail sent success data.
    private static function migrate_rm_4_8_to_rm_4_9($network_wide, $existing_rm_db_version)
    {        
        global $wpdb;
        if (floatval($existing_rm_db_version) < 4.9)
        {
            if (is_multisite() && $network_wide)
            {
                $current_blog = $wpdb->blogid;
                $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blog_ids as $blog_id)
                {
                    switch_to_blog($blog_id);
                    self::migrate_per_site_rm_4_8_to_rm_4_9();
                    restore_current_blog();
                }
            } else
            {
                self::migrate_per_site_rm_4_8_to_rm_4_9();
            }  
        }
    }

    private static function migrate_rm_5_0_to_rm_5_2($network_wide, $existing_rm_db_version)
    {        
        global $wpdb;
        
        if (floatval($existing_rm_db_version) < 5.2)
        {
            if (is_multisite() && $network_wide)
            {
                $current_blog = $wpdb->blogid;
                $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blog_ids as $blog_id)
                {
                    switch_to_blog($blog_id);
                    RM_Table_Tech::create_login_tables();
                    RM_Utilities::setup_login_form_options();
                    restore_current_blog();
                }
            } else
            {
                 RM_Table_Tech::create_login_tables();
                 RM_Utilities::setup_login_form_options();
            }  
            
            add_option('rm_option_upgraded',1);
        }
    }
    
    private static function migrate_rm_5_2_to_rm_5_3($network_wide, $existing_rm_db_version){
        global $wpdb;
        if (floatval($existing_rm_db_version) < 5.3)
        {
            if (is_multisite() && $network_wide)
            {
                $current_blog = $wpdb->blogid;
                $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blog_ids as $blog_id)
                {
                    switch_to_blog($blog_id);
                    RM_Utilities::create_recovery_page();
                    restore_current_blog();
                }
            } else
            {
                 RM_Utilities::create_recovery_page();
            }  
            add_option('rm_option_upgraded',1);
        }
    }
    
    private static function migrate_crf_to_rm_4_0($network_wide, $existing_rm_db_version, $existing_crf_db_version)
    {

        //$existing_rm_db_version = get_site_option('rm_option_db_version', false);
        //$existing_crf_db_version = get_option('crf_db_version', false);

        if (!$existing_crf_db_version)
        {
            if ($existing_rm_db_version)
            return 'already_on_rm';

            update_site_option('rm_option_db_version', RM_DB_VERSION);
            return 'no_crf_data';
        }

        if ($existing_rm_db_version)
            return 'already_on_rm';

        if ($existing_crf_db_version && !$existing_rm_db_version)
        {
            global $wpdb;

            error_log("Migrating old crf...");
            $mig = new RM_Migrator;
            $mig->migration_old_crf();

            if (is_multisite() && $network_wide)
            {

                $current_blog = $wpdb->blogid;

                $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blog_ids as $blog_id)
                {
                    switch_to_blog($blog_id);
                    error_log('curr wpdb prefix: ' . $wpdb->prefix);
                    self::migrate_per_site_crf_to_rm_4_0();
                    restore_current_blog();
                }
            } else
            {
                self::migrate_per_site_crf_to_rm_4_0();
            }

            $mig->migrate_user_meta();
        }
    }
    
    private static function migrate_per_site_rm_4_0_to_rm_4_1()
    {
        global $wpdb;
        $field_table_name = $wpdb->prefix."rm_fields";
        $wpdb->query("ALTER TABLE `$field_table_name` ADD `page_no` INT(6) NOT NULL DEFAULT '1' AFTER `form_id`");
    }
    
    private static function migrate_per_site_rm_4_1_to_rm_4_2()
    {
        global $wpdb;
        $sub_table_name = $wpdb->prefix."rm_submissions";
        $field_table_name = $wpdb->prefix."rm_fields";
        $stat_table_name = $wpdb->prefix."rm_stats";
        
        ob_start();
        $wpdb->query("ALTER TABLE `$sub_table_name` ADD `child_id` INT(6) NOT NULL DEFAULT '0' AFTER `user_email`;");
        $wpdb->query("ALTER TABLE `$sub_table_name` ADD `is_read` TINYINT(1) NOT NULL DEFAULT '1' AFTER `child_id`");
        $wpdb->query("ALTER TABLE `$sub_table_name` ADD `last_child` INT(6) NOT NULL DEFAULT '0' AFTER `child_id`");
        $wpdb->query("ALTER TABLE `$field_table_name` ADD `field_is_editable` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_field_primary`;");
        $wpdb->query("ALTER TABLE `$stat_table_name` ADD `submission_id` INT(6)");
        ob_get_clean();
    }
    
    private static function migrate_per_site_rm_4_2_to_rm_4_4()
    {
        RM_Table_Tech::repair_tables();
    }
    
    private static function migrate_per_site_rm_4_5_to_rm_4_6()
    {
        global $wpdb;
        $db_name = DB_NAME;
        $stat_table_name = $wpdb->prefix."rm_stats";
        $test_query = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$stat_table_name' AND COLUMN_NAME = 'submission_id'";
        $result = $wpdb->get_results($test_query);
        if($result == NULL || count($result) == 0)
            $wpdb->query("ALTER TABLE `$stat_table_name` ADD `submission_id` INT(6)");
    }
    
    private static function migrate_per_site_rm_4_6_to_rm_4_7()
    {
        global $wpdb;
        $stat_table_name = $wpdb->prefix."rm_stats";
        $wpdb->query("ALTER TABLE `$stat_table_name` CHANGE COLUMN `user_ip` `user_ip` VARCHAR(50)");
    }
    
    private static function migrate_per_site_rm_4_7_to_rm_4_8()
    {
        global $wpdb;
        $pplog_table_name = $wpdb->prefix."rm_paypal_logs";
        $wpdb->query("ALTER TABLE `$pplog_table_name` ADD `pay_proc` VARCHAR(50) NULL DEFAULT NULL AFTER `posted_date`, ADD `bill` LONGTEXT NULL DEFAULT NULL AFTER `pay_proc`, ADD `ex_data` LONGTEXT NULL DEFAULT NULL AFTER `bill`");
    }
    
    private static function migrate_per_site_rm_4_8_to_rm_4_9()
    {
        global $wpdb;
        $sentmails_table_name = $wpdb->prefix."rm_sent_mails";
        $wpdb->query("ALTER TABLE `$sentmails_table_name` ADD `is_read_by_user` TINYINT(1) NOT NULL DEFAULT 0 AFTER `exdata`, ADD `was_sent_success` TINYINT(1) NOT NULL DEFAULT 1 AFTER `is_read_by_user`");
    }
    
    private static function migrate_per_site_crf_to_rm_4_0()
    {
        //Start migration.
        global $wpdb;
        $step = 1200;

        error_log("IN THE PI: Migration progress log:");
        error_log("Initiating migration...");
        //require_once 'class_rm_migrator.php';
        $mig = new RM_Migrator;
        error_log("Class loaded.");
        /* error_log("Migrating old crf...");
          $mig->migration_old_crf(); */
        error_log("Migrating Global settings...");
        $mig->migrate_options();
        error_log("Migrating PayPal fields...");
        $mig->migrate_paypal_fields();



        error_log("Migrating PayPal logs...");

        $table_name = $wpdb->prefix . 'crf_paypal_log';
        $total_subs = $wpdb->get_var("SELECT COUNT(`id`) FROM $table_name WHERE 1");
        $total_loop_count = ceil((double) $total_subs / $step);
        for ($i = 0; $i <= $total_loop_count; $i++)
            $mig->migrate_paypal_logs($i * $step, $step);




        error_log("Migrating Stats...");

        $table_name = $wpdb->prefix . 'crf_stats';
        $total_subs = $wpdb->get_var("SELECT COUNT(`id`) FROM $table_name WHERE 1");
        $total_loop_count = ceil((double) $total_subs / $step);
        for ($i = 0; $i <= $total_loop_count; $i++)
            $mig->migrate_stats($i * $step, $step);


        error_log("Migrating Forms...");
        $mig->migrate_forms();
        error_log("Migrating Fields...");
        $mig->migrate_fields();
        error_log("Migrating Notes...");
        $mig->migrate_notes();

        error_log("Migrating Front users...");

        $table_name = $wpdb->prefix . 'crf_users';
        $total_subs = $wpdb->get_var("SELECT COUNT(`id`) FROM $table_name WHERE 1");
        $total_loop_count = ceil((double) $total_subs / $step);
        for ($i = 0; $i <= $total_loop_count; $i++)
            $mig->migrate_front_users($i * $step, $step);


        error_log("Migrating Submissions...");

        //ob_start();

        $table_name = $wpdb->prefix . 'crf_submissions';

        //$total_subs = $wpdb->get_var("SELECT COUNT(`id`) FROM $table_name WHERE 1");
        $count_array = $wpdb->get_results("SELECT `submission_id`, COUNT(*) AS `count` FROM `$table_name` WHERE 1 GROUP BY `submission_id`");

        $i = 0;
        $j = 0;
        $k = array();
        foreach ($count_array as $count_per_sub)
        {
            if ($j > 1200)
            {
                $k[] = $j;
                $j = 0;
            }

            $j += (int) $count_per_sub->count;
        }
        if ($j <= 1200)
            $k[] = $j;   //add any leftover submissions from the loop.

            
//ob_start();
        //var_dump($k);
        //error_log("K: ".ob_get_clean());

        foreach ($k as $kcount)
        {
            $mig->migrate_submissions($i, (int) $kcount);
            $i += (int) $kcount;
        }


        error_log("Inserting primary emails...");
        $mig->insert_primary_emails();
        error_log("Migration finished.");


        //update_option('rm_option_rm_version', RM_PLUGIN_VERSION);
        return 'migrate_success';
    }
    
    public static function first_install_proc()
    {
        global $wpdb;
        
        $existing_rm_db_version = get_site_option('rm_option_db_version', false);
        $existing_rm_plugin_version = get_site_option('rm_option_rm_version', false);
        $existing_crf_db_version = get_option('crf_db_version', false);
        
        //Check if it is fresh RM installation
        if(!$existing_rm_db_version && !$existing_rm_plugin_version)
        {
            //Insert sample data only if CRF is not there as well, otherwise migration might cause issues.
            if(!$existing_crf_db_version)
            {
                $datafile = RM_EXTERNAL_DIR."sample_data.xml";
                $id = RM_Services::import_form_first($datafile);
                $id = RM_Services::import_form_first($datafile, intval($id));

                //Now get the ids of these forms and save them so we can check in future if given form is sample form or not.
                //Usecase: Form manager template requires exclusive ids for sample form cards.

                $inserted_sample_data = new stdClass;
                $inserted_sample_data->forms = array();
                $form_table = RM_Table_Tech::get_table_name_for('FORMS');
                $sfids = $wpdb->get_results("SELECT `form_id`, `form_type` FROM $form_table ORDER BY `form_id` DESC LIMIT 2");

                if($sfids && is_array($sfids))
                {
                    foreach($sfids as $sfid)
                    {
                        $inserted_sample_data->forms[] = (object)array('form_id'=>$sfid->form_id, 'form_type'=>$sfid->form_type);                    
                    }                
                }

                update_site_option('rm_option_inserted_sample_data', $inserted_sample_data);   
            }
            
            update_site_option('rm_option_install_date', time());
            update_site_option('rm_option_install_type', 'gold');
        }
        else
        {
            //set tours as taken.
            RM_Utilities::update_tour_state('form_manager_tour', 'taken');
            RM_Utilities::update_tour_state('form_gensett_tour', 'taken');
            RM_Utilities::update_tour_state('form_setting_dashboard_tour', 'taken');
            RM_Utilities::update_tour_state('submissions_tour', 'taken');
        }
                
    }

}
