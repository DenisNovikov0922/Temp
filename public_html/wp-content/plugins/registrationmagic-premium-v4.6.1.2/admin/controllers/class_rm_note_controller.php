<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_rm_note_controller
 *
 * @author CMSHelplive
 */
class RM_Note_Controller
{

    private $mv_handler;

    public function __construct()
    {
        $this->mv_handler = new RM_Model_View_Handler();
    }

    public function add($model, RM_Note_Service $service, $request, $params)
    {
        if (isset($request->req['rm_submission_id']) && $request->req['rm_submission_id'])
            if ($this->mv_handler->validateForm("add-note"))
            {
                $model->set($request->req);
               
                if (isset($request->req['rm_note_id']))
                {
                    $service->update($request->req['rm_note_id']);
                    
                } else
                {
                    $service->add();
                }
                $service->notify_users($model);

                RM_Utilities::redirect('?page=rm_submission_view&rm_submission_id=' . $request->req['rm_submission_id']);
            } else
            {
                if (isset($request->req['rm_note_id']))
                    $model->load_from_db($request->req['rm_note_id']);

                $data = new stdClass();
                $data->model = $model;
                $data->type=isset($request->req['rm_note_type'])?$request->req['rm_note_type']:null;
                $data->submission_id = $request->req['rm_submission_id'];
                $view = $this->mv_handler->setView('add_note');
                $view->render($data);
            } else
            throw new InvalidArgumentException("Invalid Submission id in " . __CLASS__ . "::" . __FUNCTION__);
    }

    public function delete($model, RM_Note_Service $service, $request, $params)
    {
        if (isset($request->req['rm_note_id']))
            $service->remove($request->req['rm_note_id'],'NOTES');
        RM_Utilities::redirect('?page=rm_submission_view&rm_submission_id=' . $request->req['rm_submission_id']);
    }

}
