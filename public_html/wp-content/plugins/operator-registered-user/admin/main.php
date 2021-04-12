<?php
/**
 * Includes functions for all admin page templates.
 * functions that add menu pages in the dashboard.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Opregusr_Admin {
	
	/**
	 * Class Construct.
	 *
	 * @since 1.0
	 */	
	public function __construct() {

		//Admin Menu
		add_action( 'admin_menu', array($this, 'opregusr_admin_menu'), 9 );

		
		//Add content link
		add_filter( "plugin_action_links", array($this, 'opregusr_add_context_link'), 10, 2 );
	}
	
	public function opregusr_add_context_link( $links, $file ){
		
		if($file == plugin_basename(OPREGUSR_PLUGIN_FILE) && function_exists('admin_url') ) {
		$settings_link = '<a href="'.admin_url('options-general.php?page=opregusr').'">'.esc_html__('Settings', 'opregusr-text-domain').'</a>';
		array_unshift( $links, $settings_link );
		}
		return $links;
	}
	
	
	public function opregusr_admin_menu(){
	
		$label = esc_html__('SME Factory - Registrazioni', 'opregusr-text-domain');
				
		add_options_page(
			$label, // page_title
			$label, // menu_title
			'manage_options', // capability
			'opregusr', // menu_slug
			array( $this, 'opregusr_admin_settings_page' ) // function
		);
		
		
	}
	public function opregusr_admin_settings_page(){
		
		$message = '';
		if (isset($_POST['opregusr_settings'])) {
			
			if( wp_verify_nonce( $_POST['opregusr_settings'], "opregusr-api-setting" ) ){
				//echo '<pre>'; print_r($_POST); exit;
				$_POST['opregusr']['form_page'] = trim($_POST['opregusr']['form_page']);
				$_POST['opregusr']['form_page'] = trim($_POST['opregusr']['form_page'], '/');
				
								
				$data = (array)($_POST['opregusr']);
				$ack = update_option('_opregusr_settings', $data);
				$message = '<div id="message" class="updated notice notice-success"><p>'.esc_html__('Settings Updated.', 'opregusr-text-domain').'</p></div>';
				
			}else{
				$message = '<div id="message" class="notice notice-error"><p>'.esc_html__('Error Saving Settings.', 'opregusr-text-domain').'</p></div>';
			}
		}
		
		
		$default = array('form_page'=>'', 'tform'=>'0', 'user_role'=>'administrator', 'force_login'=>'n');
		$settings = get_option('_opregusr_settings', false);
		if($settings != false){
			$default = array_merge($default, $settings);
		}
	?>	
    <style type="text/css">
	#opregusr-wrap .flex-container {
		display: flex;
	}
	#opregusr-wrap .flex-child {
		flex: 1;
	}  
	#opregusr-wrap .flex-child:first-child {
		margin-right: 10px;
	} 
	#opregusr-wrap  .info-block{
		border: 1px solid #ccc;
	}
	#opregusr-wrap h2.the-head-label img{vertical-align: text-top;}
	#opregusr-wrap .onthe-border-label{
		text-align:center;
	}
	#opregusr-wrap .onthe-border-label::after {
		content: '';
		display: block;
		flex: 1;
		height: 1px;
		background-color: #ccc;
		margin-left: 5px;
		margin-right: 5px;
	}
	#opregusr-wrap .dashicons{
		font-size: 16px;
		margin-top: 3px;
		color: #228B22;
		opacity: 0.5;
	}
	#opregusr-wrap .pleft{
		padding-left:15px;	
	}
	#opregusr-wrap code{background-color: #EEE8AA;}
	#opregusr-wrap table{width: 100%;}
	#opregusr-wrap .regular-text, #opregusr-wrap .regular-select{
		width: 100%;
		max-width: 360px;
	}
	#opregusr-wrap .submit{
		margin-left: 10px;
	}
	#opregusr-wrap .example{
		margin-bottom: 5px;
		display: inline-block;
		width:100%;
	}
	#opregusr-wrap .example.ex1{margin-top:5px;}
	#opregusr-wrap td.ib{display:inline-block; margin-right:25px;}
	#opregusr-wrap .note-icon{font-size:20px;vertical-align: sub;opacity: 0.7; color:red;}
	#opregusr-wrap .note-head{line-height:20px;}
	
	#opregusr-wrap .hide{display:none !important;}
	#opregusr-wrap .pleft img{max-width:100%;}
	#opregusr-wrap .divider-div{height:6px;}
	</style>
	<div class="wrap" id="opregusr-wrap">
        <h2 class="the-head-label">Dashboard SME Factory</h2>
        <hr/>
        <p></p>
        <?php echo $message; ?>
        <div class="flex-container">
		<div class="flex-child left-block settings-block">
        <form method="post" action="options-general.php?page=opregusr">
              
      <table>
      
      <tr valign="top">
      <th scope="row"></th>
      <td>
      <h3><?php _e('Form SME Factory', 'opregusr-text-domain'); ?></h3>
      <select name="opregusr[tform]" id="display-mode-select" class="regular-select">
      <?php
		$tforms = $this->get_form_options($default['tform']);
		echo $tforms;
	  ?>
      </select>
      </td>
      </tr>
      
      <tr valign="top">
      <th scope="row"></th>
      <td>
      <h3><?php _e('Pagina sul quale è presente il Form', 'opregusr-text-domain'); ?></h3>
      <input type="text" id="opregusr_fslug" class="regular-text" name="opregusr[form_page]" 
      value="<?php echo $default['form_page']; ?>" placeholder="<?php _e('', 'opregusr-text-domain'); ?>" 
      required="required"/>
      </td>
      </tr>
      
      <tr valign="top">
      <th scope="row"></th>
      <td>
      <h3><?php _e('Ruolo utente che può vedere il Form', 'opregusr-text-domain'); ?></h3>
      <select name="opregusr[user_role]" id="display-mode-select" class="regular-select">
      <?php 
	  $roles = wp_roles();
	  $uroles = $roles->roles;
	  foreach($uroles as $rkey=>$rval):
	  
	  if( $default['user_role']==$rkey){
		echo '<option selected="selected" value="'.$rkey.'">'.$rval['name'].'</option>';  
	  }else{
	  	echo '<option value="'.$rkey.'">'.$rval['name'].'</option>';
	  }
	  
	  endforeach;
	  ?>
      </select>
      </td>
      </tr>
   
           
      </table>
      <p class="submit">
      <input type="submit" name="submit" id="submit" class="button button-primary" value="Salva">
      </p>
      
      <input type="hidden" name="opregusr_settings" value="<?php echo wp_create_nonce('opregusr-api-setting'); ?>"/>
      </form>
        </div>
        <div class="flex-child right-block info-block">
        
        <div class="onthe-border-label"> 
        <i class="dashicons dashicons-info"></i> 
		<?php echo esc_html__('Informazioni', 'opregusr-text-domain' );?> 
        </div>
        
        <p class="pleft">
        <span class="note-icon">&#9728;</span>
        <span class="note-head">NON MODIFICARE le impostazioni a fianco
        </span>
        </p>
        
        <div class="divider-div"></div>
             
        <div class="pleft">
        <span class="note-icon">&#9728;</span>
		<span class="note-head">Rimuovendo le righe da questa tabella NON ELIMINERAI i clienti o le submission (iscrizioni)</span>    
                             
        </div>     
                
        </div>
        </div>
        
        <div class="divider-div"></div>
        <div class="divider-div"></div>
        <div class="info-block" style="padding: 5px;">
        <div class="onthe-border-label"> 
        <i class="dashicons dashicons-info"></i> 
		<?php echo esc_html__('Lista Iscrizioni', 'opregusr-text-domain' );?> 
        </div>
        
        <form method="post" autocomplete="off">
            <input type="hidden" name="list_page" value="">
                        
            <?php
                $data_list = new Opregusr_List();
                $data_list->prepare_items();
                
                //$data_list->search_box( 'search', 'product_search' );                   
                $data_list->display(); 
            ?>
        </form>
        
        </div>
        
    </div>
			
	<?php	
	}
	
	public function get_form_options($select_form=0){
		
		global $wpdb;
		$table = $wpdb->prefix.'rm_forms';
		$query = "SELECT * FROM `{$table}`";
		
		$rows = $wpdb->get_results($query, ARRAY_A);
		
		$html = '';
		if(is_array($rows) && !empty($rows))
		{
			foreach($rows as $row){
				if($select_form == $row['form_id']){
					$html .= '<option selected="selected" value="'.$row['form_id'].'">'.$row['form_name'].'</option>';
				}else{
					$html .= '<option value="'.$row['form_id'].'">'.$row['form_name'].'</option>';
				}
			}
		}
		
		return $html;
		
	}
}

/**
* Class for listing data using wordpress list table class
*/
class Opregusr_List extends WP_List_Table {
	
	var $list_data = array();
	var $table_name = 'rm_submissions_operator';

    function __construct(){
		
    	global $status, $page, $wpdb, $wp_standard_class;
				
		$this->list_data = $this->getData();
		
		parent::__construct( array(
			'singular'  => 'item',     //singular name of the listed records
			'plural'    => 'items',    //plural name of the listed records
			'ajax'      => false        //does this table support ajax?
		) );
    }
	
	public function getData()
	{
		global $wpdb;
		//var_dump($_POST); exit;
		$where = ' WHERE 1=1 ';
		if( isset($_POST['s']) && !empty($_POST['s']) )
		{ $find=esc_attr( $_POST['s'] ); $where .= " AND `operator_user` = '{$find}' ";}
		
		
		$data_query = 'SELECT * FROM ' .$wpdb->prefix . $this->table_name . $where; 
		$rows = $wpdb->get_results( $data_query, OBJECT );    
		
		$dataPoints = array();
		
		foreach($rows as $row)
		{	
			$dataPoints[] = array('ID'=>$row->id, 'operator'=>$row->operator_user, 'submission_id'=>$row->submission_id, 'registered_user'=>$row->registered_user, 'date_created'=>$row->date_created, 'details'=>'');
		}
		
		return $dataPoints;
	}
	

   function column_default($item, $column_name){
        return $item[$column_name];
    }
    
	/**
	* Function with column_fieldname namming
	* to edit/modify its value before showing
	* In this case our name column
	*/
    function column_operator($item){ 

		$user_id = $item['operator'];
		$user_obj = get_user_by('id', $user_id);
		$user_obj = get_userdata($user_id);
		
		if($user_obj!=false){
	
			$first_name = get_user_meta( $user_id, 'first_name', true );
			$last_name = get_user_meta( $user_id, 'last_name', true );
			//var_dump($first_name);
			
			$value = (isset($first_name) && $first_name!='')?$first_name.' '.$last_name : ucfirst($user_obj->data->display_name);
			
		}else{
			$value = '';	
		}
	
		$delete_warning = esc_html__('Are you sure?', 'opregusr-text-domain');

        $actions = array(
            
			'delete'    => sprintf('<a onclick="return confirm(\'%s\')" href="?page=%s&action=%s&data_id=%s">Delete</a>', 
									$delete_warning, $_REQUEST['page'],'delete',$item['ID']),
        );
        
        return sprintf('%1$s <span style="color:silver"> (%2$s)</span>%3$s',
            /*$1%s*/ $value,
					 $item['operator'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }
	
	function column_registered_user($item){ 
		
		$user_id = $item['registered_user'];
		$user_obj = get_user_by('id', $user_id);
	
		if($user_obj==false){ return ''; }
			
		return $item['registered_user'] . ' <small><a target="_blank" href="user-edit.php?user_id='.$user_id.'">('.$user_obj->user_email.')<a/></small>';
	}
	
	function column_submission_id($item){
		$submission_id = (int)$item['submission_id'];
		$html = $submission_id. ' (<a target="_blank" href="admin.php?page=rm_submission_view&rm_submission_id='.$submission_id.'">Dettagli</a>)';
		return $html;
	}
	
	function column_date_created($item){
		return date("F j, Y, g:i a",strtotime($item['date_created'])); 
	}
		
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['plural'],  
            /*$2%s*/ $item['ID']                
        );
    }
    
   
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', 
            'operator'   => esc_html__('Operatore', 'wpezilimt-text-domain'),
			'registered_user' => esc_html__('Cliente Registrato', 'wpezilimt-text-domain'),
			'submission_id'   => esc_html__('Submission', 'wpezilimt-text-domain'),
			'date_created'   => esc_html__('Data', 'wpezilimt-text-domain'),
        );
        return $columns;
    }
    
    function get_sortable_columns() {
        $sortable_columns = array(
            'submission_id' => array('submission_id',true),    //true means its already sorted
            'operator'    => array('operator',false)
        );
        return $sortable_columns;
    }
    
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Elimina'
        );
        return $actions;
    }
    
    function process_bulk_action() {
        global $wpdb;
		$table = $wpdb->prefix . $this->table_name;
        if( 'delete'===$this->current_action() ) {
						
			if( isset($_POST['items']) && is_array($_POST['items']))
			{
				$data_ids = $_POST['items'];
				foreach($data_ids as $id)
				{
					$query = "DELETE FROM $table WHERE `id`=$id";
					$wpdb->query($query);						
				}
				
				$_SESSION['display_msg']   = esc_html__('Items deleted successfully.', 'wpezilimt-text-domain');
			}
			else
			{
				$data_id = (int)$_GET['data_id'];				
				$query = "DELETE FROM $table WHERE `id`=$data_id";
				$wpdb->query($query);			
				
				$_SESSION['display_msg']   = esc_html__('Item deleted successfully.', 'wpezilimt-text-domain');		
			}
			
			
			$_SESSION['msg_type']      = 'success';
			wp_redirect('options-general.php?page=opregusr',301);
        }
		
		       
    }
	
	
    function prepare_items() {
        
        $per_page = 10;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        $this->process_bulk_action();
        
        $data = $this->list_data;
                
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ID'; 
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; 
            $result = strcmp($a[$orderby], $b[$orderby]); 
            return ($order==='asc') ? $result : -$result; 
        }
        usort($data, 'usort_reorder');
        
       
        $current_page = $this->get_pagenum();
        
       
        $total_items = count($data);
        
        
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        
        $this->items = $data;

        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}