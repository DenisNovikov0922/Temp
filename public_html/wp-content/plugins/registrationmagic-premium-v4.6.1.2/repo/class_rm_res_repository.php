<?php
/**
 * Centralizing Resources related database operations
 * Resource denotes data saved inside resource table.
 */
class RM_Res_Repository {
     
    protected $def_res = array('res_id'=>null,'type'=>null,'data'=>null,'meta'=>null);
    protected $data_specifiers = array('%d', '%s', '%s', '%s');
    
    /**
      * @param array $res (array with resourse data)
      * @return int|bool id of resourse or false if failed
      */
    public function insert(array $res)
    {
        //Fill in missing values with defaults
        $data = array_merge( $this->def_res, $res);
        
        if(!$data->type)
            return false;
        
        //Remove any unwanted keys if present
        $data = array_intersect_key($data, $this->def_res);
        
        //Remove 'res_id' entries as they are not needed for row creation.
        $data = array_slice($data,1);
        $data_specifiers = array_slice($this->data_specifiers, 1);
        
        return RM_DBManager::insert_row('RES', $data, $data_specifiers);
    }
    
     /**
      * @param int $id (array with form_id and group by clause)
      * @return array (list of users)
      */  
    public function get_by_id($res_id)
    {        
        return RM_DBManager::get_row('RES', $res_id);       
    }
    
    /**
      * @param int $id (array with form_id and group by clause)
      * @return void
      */  
    public function delete_by_id($res_id)
    {        
        return RM_DBManager::remove_row('RES', $res_id);       
    }
    
    /*
     * offset has no effect if limit is not supplied or null
     * @return array
     */
    public function get($where_clause, $limit = null, $offset = 0)
    {        
       global $wpdb;

       $res_table = RM_Table_Tech::get_table_name_for('RES');
       
       // Limit result set
       if($limit)
           $limit_query= "LIMIT $limit OFFSET $offset";
       else
           $limit_query = "";       

       $qry = "SELECT * from $res_table WHERE $where_clause $limit_query";
       
       $results = $wpdb->get_results($qry);
       
       if(!$results || !is_array($results))
           return array();
       else
           return $results;
    }

}
