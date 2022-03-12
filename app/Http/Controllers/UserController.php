<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\BaseController;
use App\Http\Requests\UserFormRequest;
use App\Services\RoleService;

class UserController extends BaseController
{
    protected $role;
    public function __construct(UserService $user, RoleService $role)
    {
        $this->service = $user;
        $this->role = $role;
    }


    /**
     * load user index view
     */
    public function index(){
        if(permission('user-access')){
            $this->setPageData('User', 'User', 'fas fa-th-list');
            $roles = $this->role->index();
            return view('user.index', compact('roles'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request){
        if(permission('user-access')){
            if($request->ajax()){
                $output = $this->service->get_datatable_data($request);
            }else{
                $output = ['status' => 'error', 'message' => 'Unauthorized access blocked'];
            }

            return response()->json($output);
        }
    }

    public function store_or_update_data(UserFormRequest $request){
        if($request->ajax()){
            if(permission('user-access') || permission('user-edit')){
                $result = $this->service->store_or_update_data($request);

                if($result){
                    return $this->response_json($status = 'success',$message = 'Data has been saved successfully',$data=null, $response_code=201);
                }else{
                    return $this->response_json($status = 'error',$message = 'Can\'t delete data',$data=null, $response_code=204);
                }
            }else{
                return $this->response_json($status = 'error',$message = 'Unauthorized access blocked',$data=null, $response_code=401);
            }
        }else{
            return $this->response_json($status = 'error',$message = null,$data=null, $response_code=401);
        }
    }

    public function edit(Request $request){
        if($request->ajax()){
            if(permission('user-edit')){
                $data = $this->service->edit($request);

                if($data->count()){
                    return $this->response_json($status = 'success',$message = null,$data=$data, $response_code=201);
                }else{
                    return $this->response_json($status = 'error',$message = 'No data found',$data=null, $response_code=204);
                }
            }else{
                return $this->response_json($status = 'error',$message = 'Unauthorized access blocked',$data=null, $response_code=401);
            }
        }else{
            return $this->response_json($status = 'error',$message = null,$data=null, $response_code=401);
        }
    }

    public function show(Request $request){
        if($request->ajax()){
            if(permission('user-view')){
                $user = $this->service->edit($request);
                return view('user.details', compact('user'))->render();
            }
        }
    }

    public function delete(Request $request){
        if($request->ajax()){
            if(permission('user-delete')){
                $result = $this->service->delete($request);

                if($result){
                    return $this->response_json($status = 'success',$message = 'Data has been deleted',$data=null, $response_code=201);
                }else{
                    return $this->response_json($status = 'error',$message = 'Can\'t delete data',$data=null, $response_code=204);
                }
            }else{
                return $this->response_json($status = 'error',$message = 'Unauthorized access blocked',$data=null, $response_code=401);
            }
        }else{
            return $this->response_json($status = 'error',$message = null,$data=null, $response_code=401);
        }
    }

    public function bulk_delete(Request $request){
        if($request->ajax()){
            if(permission('user-bulk-delete')){
                $result = $this->service->bulk_delete($request);

                if($result){
                    return $this->response_json($status = 'success',$message = 'Data has been deleted',$data=null, $response_code=201);
                }else{
                    return $this->response_json($status = 'error',$message = 'Can\'t delete data',$data=null, $response_code=204);
                }
            }else{
                return $this->response_json($status = 'error',$message = 'Unauthorized access blocked',$data=null, $response_code=401);
            }
        }else{
            return $this->response_json($status = 'error',$message = null,$data=null, $response_code=401);
        }
    }

    public function change_status(Request $request){
        if($request->ajax()){
            if(permission('user-edit')){
                $result = $this->service->change_status($request);

                if($result){
                    return $this->response_json($status = 'success',$message = 'Status has been updated',$data=null, $response_code=201);
                }else{
                    return $this->response_json($status = 'error',$message = 'Can\'t update status',$data=null, $response_code=204);
                }
            }else{
                return $this->response_json($status = 'error',$message = 'Unauthorized access blocked',$data=null, $response_code=401);
            }
        }else{
            return $this->response_json($status = 'error',$message = null,$data=null, $response_code=401);
        }
    }
}
