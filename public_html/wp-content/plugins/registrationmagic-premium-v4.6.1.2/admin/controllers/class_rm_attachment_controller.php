<?php

/**
 * Controller to handle attachment related work
 *
 * @author CMSHelplive
 */
class RM_Attachment_Controller
{

    public $mv_handler;

    public function __construct()
    {
        $this->mv_handler = new RM_Model_View_Handler();
    }

    public function manage($model, $service, $request, $params)
    {

        if (isset($request->req['rm_action']) && $request->req['rm_action'] === 'delete')
            if (isset($request->req['rm_att_id']))
                wp_delete_attachment($request->req['rm_att_id']);

        $data = new stdClass();
        if (isset($request->req['rm_form_id']))
            $form_id = $request->req['rm_form_id'];
        else
            $form_id = $service->get('FORMS', 1, array('%d'), 'var', 0, 15, $column = 'form_id', null, true);
        $data->attachments = $service->get_all_form_attachments($form_id);
        $data->form_id = $form_id;
        $data->forms = RM_Utilities::get_forms_dropdown($service);

        /*         * ** Terrific's awesome pagination *** */

        $data->entries_per_page = $entries_per_page = 10;
        $req_page = (isset($request->req['rm_reqpage']) && $request->req['rm_reqpage'] > 0) ? $request->req['rm_reqpage'] : 1;
        $data->offset = $offset = ($req_page - 1) * $entries_per_page;
        if(is_array($data->attachments)){
            $data->total_entries = $total_entries = count($data->attachments);
        }
        else
        {
            $data->total_entries =0;
        }
        
        $data->total_pages = (int) ($data->total_entries / $entries_per_page) + (($data->total_entries % $entries_per_page) == 0 ? 0 : 1);
        $data->curr_page = $req_page;
        $data->rm_slug = $request->req['page'];

        $data->entries_on_this_page = ($req_page >= $data->total_pages) ? (($data->total_entries % $entries_per_page) == 0 ? $entries_per_page : ($total_entries % $entries_per_page)) : $entries_per_page;

        /*         * ************************************ */

        $view = $this->mv_handler->setView('attachment_manage');
        $view->render($data);
    }

    public function download_all($model, $service, $request, $params)
    {
        if (isset($request->req['form_id']))
            $form_id = $request->req['form_id'];
        else
            throw new InvalidArgumentException(__('No Form ID is provided to ','registrationmagic-gold') . __CLASS__ . "::" . __FUNCTION__ . ".");

        $attachments = $service->get_all_form_attachments($form_id);

        if ($attachments)
        {
            $zipped_file = $service->get_zip($attachments);
        
            if(!$zipped_file)
                return;
        }
        else
            return;

        $download = $service->download_file($zipped_file);
        
        return $download;
    }

    public function download_selected($model, $service, $request, $params)
    {
        $selected = isset($request->req['rm_selected']) ? $request->req['rm_selected'] : null;

        if ($selected)
        {
            $zipped_file = $service->get_zip($selected);
            
            if(!$zipped_file)
                return;
        }
        else
            return;

        $download = $service->download_file($zipped_file);
        
        return $download;
    }

    public function download($model, RM_Attachment_Service $service, $request, $params)
    {
        $selected = isset($request->req['rm_att_id']) ? $request->req['rm_att_id'] : null;

        if ($selected)
            $attachment = get_attached_file($selected);
        else
            return;

        $download = $service->download_file($attachment,false);
    }

}
