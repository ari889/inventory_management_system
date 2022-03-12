<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Http\Controllers\BaseController;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RoleUpdateRequest;

class RoleController extends BaseController
{
    public function __construct(RoleService $role)
    {
        $this->service = $role;
    }


    /**
     * load menu index view
     */
    public function index(){
        $this->setPageData('Role', 'Role', 'fas fa-th-list');
        return view('role.index');
    }

    public function get_datatable_data(Request $request){
        if($request->ajax()){
            $output = $this->service->get_datatable_data($request);
        }else{
            $output = ['status' => 'error', 'message' => 'Unauthorized access blocked'];
        }

        return response()->json($output);
    }

    /**
     * load menu index view
     */
    public function create(){
        $this->setPageData('Create Role', 'Create Role', 'fas fa-th-list');
        $data = $this->service->permission_module_list();
        return view('role.create', compact('data'));
    }

    public function store_or_update_data(RoleRequest $request){
        if($request->ajax()){
            $result = $this->service->store_or_update_data($request);

            if($result){
                return $this->response_json($status = 'success',$message = 'Data has been saved successfully',$data=null, $response_code=201);
            }else{
                return $this->response_json($status = 'error',$message = 'Can\'t delete data',$data=null, $response_code=204);
            }
        }else{
            return $this->response_json($status = 'error',$message = null,$data=null, $response_code=401);
        }
    }

    public function edit(int $id){
        $this->setPageData('Edit Role', 'Edit Role', 'fas fa-th-list');
        $data = $this->service->permission_module_list();
        $permission_data = $this->service->edit($id);
        return view('role.edit', compact('data', 'permission_data'));
    }

    public function show(int $id){
        $this->setPageData('Role Details', 'Role Details', 'fas fa-th-list');
        $data = $this->service->permission_module_list();
        $permission_data = $this->service->edit($id);
        return view('role.view', compact('data', 'permission_data'));
    }

    public function delete(Request $request){
        if($request->ajax()){
            $result = $this->service->delete($request);

            if($result == 1){
                return $this->response_json($status = 'success',$message = 'Data has been deleted',$data=null, $response_code=201);
            }else if($result == 2){
                return $this->response_json($status = 'error',$message = "Data can't delete because it's associate with many users.",$data=null, $response_code=204);
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

            if($result['status'] == 1){
                return $this->response_json($status = 'success',$message = !empty($result['message']) ? $result['message'] : 'Data has been deleted',$data=null, $response_code=201);
            }else if($result['status'] == 2){
                return $this->response_json($status = 'error',$message = !empty($result['message']) ? $result['message'] : "Selected data Can\'t delete.",$data=null, $response_code=204);
            }else{
                return $this->response_json($status = 'error',$message = !empty($result['message']) ? $result['message'] : 'Selected data Can\'t delete',$data=null, $response_code=204);
            }
        }else{
            return $this->response_json($status = 'error',$message = null,$data=null, $response_code=401);
        }
    }
}
