<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PermissionService;
use App\Http\Controllers\BaseController;
use App\Http\Requests\PermissionRequest;
use App\Http\Requests\PermissionUpdateRequest;

class PermissionController extends BaseController
{
    public function __construct(PermissionService $permission)
    {
        $this->service = $permission;
    }


    /**
     * load menu index view
     */
    public function index(){
        $this->setPageData('Permission', 'Permission', 'fas fa-th-list');
        $data = $this->service->index();
        return view('permission.index', compact('data'));
    }

    public function get_datatable_data(Request $request){
        if($request->ajax()){
            $output = $this->service->get_datatable_data($request);
        }else{
            $output = ['status' => 'error', 'message' => 'Unauthorized access blocked'];
        }

        return response()->json($output);
    }

    public function store(PermissionRequest $request){
        if($request->ajax()){
            $result = $this->service->store($request);

            if($result){
                return $this->response_json($status = 'success',$message = 'Data has been saved successfully',$data=null, $response_code=201);
            }else{
                return $this->response_json($status = 'error',$message = 'Can\'t delete data',$data=null, $response_code=204);
            }
        }else{
            return $this->response_json($status = 'error',$message = null,$data=null, $response_code=401);
        }
    }

    public function edit(Request $request){
        if($request->ajax()){
            $data = $this->service->edit($request);

            if($data->count()){
                return $this->response_json($status = 'success',$message = null,$data=$data, $response_code=201);
            }else{
                return $this->response_json($status = 'error',$message = 'No data found',$data=null, $response_code=204);
            }
        }else{
            return $this->response_json($status = 'error',$message = null,$data=null, $response_code=401);
        }
    }

    public function update(PermissionUpdateRequest $request){
        if($request->ajax()){
            $result = $this->service->update($request);

            if($result){
                return $this->response_json($status = 'success',$message = 'Data has been updated successfully',$data=null, $response_code=201);
            }else{
                return $this->response_json($status = 'error',$message = 'Can\'t update data',$data=null, $response_code=204);
            }
        }else{
            return $this->response_json($status = 'error',$message = null,$data=null, $response_code=401);
        }
    }

    public function delete(Request $request){
        if($request->ajax()){
            $result = $this->service->delete($request);

            if($result){
                return $this->response_json($status = 'success',$message = 'Data has been deleted',$data=null, $response_code=201);
            }else{
                return $this->response_json($status = 'error',$message = 'Can\'t delete data',$data=null, $response_code=204);
            }
        }else{
            return $this->response_json($status = 'error',$message = null,$data=null, $response_code=401);
        }
    }

    public function bulk_delete(Request $request){
        if($request->ajax()){
            $result = $this->service->bulk_delete($request);

            if($result){
                return $this->response_json($status = 'success',$message = 'Data has been deleted',$data=null, $response_code=201);
            }else{
                return $this->response_json($status = 'error',$message = 'Can\'t delete data',$data=null, $response_code=204);
            }
        }else{
            return $this->response_json($status = 'error',$message = null,$data=null, $response_code=401);
        }
    }
}
