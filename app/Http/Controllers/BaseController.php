<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BaseController extends Controller
{
    protected $output;
    protected $service;

    /**
     * @param $page_title
     * @param $sub_title
     * @param $page_icon
     *
     * for view share
     */
    protected function setPageData($page_title, $sub_title, $page_icon){
        view()->share(['page_title' => $page_title, 'sub_title' => $sub_title, 'page_icon' => $page_icon]);
    }

    /**
     * set datatable default property
     */
    protected function set_datatable_default_property(Request $request)
    {
        $this->model->setOrderValue($request->input('order.0.column'));
        $this->model->setDirValue($request->input('order.0.dir'));
        $this->model->setLengthValue($request->input('length'));
        $this->model->setStartValue($request->input('start'));
    }

    /**
     * who update this data
     */
    protected function track_data($collection, $update_id=null){
        $created_by = $updated_by = auth()->user()->name;
        $created_at = $updated_at = Carbon::now();
        return $update_id ? $collection->merge(compact('updated_by','updated_at'))
        : $collection->merge(compact('created_by','created_at'));
    }

    /**
     * store message
     */
    protected function store_message($result, $update_id=null)
    {
        return $result ? ['status'=>'success','message'=> !empty($update_id) ? 'Data has been updated successfully' : 'Data has been saved successfully']
        : ['status'=>'error','message'=> !empty($update_id) ? 'Failed to update data' : 'Falied to save data'];
    }

    /**
     * draw data table data
     */
    protected function datatable_draw($draw, $recordsTotal, $recordsFiltered, $data)
    {
        return array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
    }


    /**
     * @param $error_code
     * @param $message
     * @return response
     *
     * for show error
     */
    protected function showErrorPage($error_code=404, $message=null){
        $data['message'] = $message;
        return response()->view('errors.'.$error_code, $data, $error_code);
    }


    /**
     * @param $status
     * @param $message
     * @param $data
     * @param $response_code
     *
     * return json response
     */
    protected function response_json($status='success', $message=null, $data=null, $response_code=200){
        return response()->json([
            'status'        => $status,
            'message'       => $message,
            'data'          => $data,
            'response_code' => $response_code
        ]);
    }

    protected function access_blocked(){
        return ['status' => 'error', 'message' => 'Unauthorize access blocked.'];
    }

    protected function unauthorized_access_blocked(){
        return redirect('unauthorized');
    }

    protected function data_message($data){
        return $data ? $data : ['status' => 'error', 'message' => 'No data found!'];
    }

    protected function delete_message($result)
    {
        return $result ? ['status'=>'success','message'=> 'Data has been deleted successfully']
        : ['status'=>'error','message'=> 'Failed to delete data'];
    }

    protected function bulk_delete_message($result)
    {
        return $result ? ['status'=>'success','message'=> 'Selected data has been deleted successfully']
        : ['status'=>'error','message'=> 'Failed to delete selected data'];
    }
}
