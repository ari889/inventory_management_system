<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MenuService;
use App\Http\Controllers\BaseController;
use App\Http\Requests\MenuRequest;
use App\Services\ModuleService;

class MenuController extends BaseController
{
    protected $module;
    public function __construct(MenuService $menu, ModuleService $module)
    {
        $this->service = $menu;
        $this->module = $module;
    }


    /**
     * load menu index view
     */
    public function index(){
        if(permission('menu-access')){
            $this->setPageData('Menu', 'Menu', 'fas fa-th-list');
            return view('menu.index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request){
        if(permission('menu-access')){
            if($request->ajax()){
                $output = $this->service->get_datatable_data($request);
            }else{
                $output = ['status' => 'error', 'message' => 'Unauthorized access blocked'];
            }

            return response()->json($output);
        }
    }

    public function store_or_update_data(MenuRequest $request){
        if($request->ajax()){
            if(permission('menu-add') || permission('menu-edit')){
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
            if(permission('menu-edit')){
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

    public function delete(Request $request){
        if($request->ajax()){
            if(permission('menu-delete')){
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
            if(permission('menu-bulk-delete')){
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

    public function orderItem(Request $request){
        $menuItemOrder = json_decode($request->input('order'));
        $this->service->orderMenu($menuItemOrder, null);
        $this->module->restore_session_module();
    }
}
