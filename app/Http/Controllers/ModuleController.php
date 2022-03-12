<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ModuleService;
use App\Http\Controllers\BaseController;
use App\Http\Requests\ModuleRequest;

class ModuleController extends BaseController
{
    public function __construct(ModuleService $module)
    {
        $this->service = $module;
    }


    /**
     * load menu index view
     */
    public function index(int $id){
        if(permission('menu-builder')){
            $this->setPageData('Menu Builder', 'Menu Builder', 'fas fa-th-list');
            $data = $this->service->index($id);
            return view('module.index', compact('data'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function create($menu){
        if(permission('menu-module-add')){
            $this->setPageData('Create Menu Module', 'Add Menu Module', 'fas fa-th-list');
            $data = $this->service->index($menu);
            return view('module.form', compact('data'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function storeOrUpdate(ModuleRequest $request){
        if(permission('menu-module-add') || permission('menu-module-edit')){
            $result = $this->service->store_or_update_data($request);

            if($result){
                if($request->update_id){
                    session()->flash('success', 'Module updated successfully');
                }else{
                    session()->flash('success', 'Module created successfully');
                }
                return redirect('menu/builder/'.$request->menu_id);
            }else{
                if($request->update_id){
                    session()->flash('error', 'Module failed to update');
                }else{
                    session()->flash('error', 'Module failed to create');
                }
                return back();
            }
        }else{
            return $this->unauthorized_access_blocked();
        }
    }


    public function edit($menu, $module){
        if(permission('menu-module-edit')){
            $this->setPageData('Update Menu Module','Update Menu Module','fas fa-th-list');
            $data = $this->service->edit($menu,$module);
            return view('module.form',compact('data'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function destroy($module){
        if(permission('menu-module-delete')){
            $result = $this->service->delete($module);
            if($result){
                session()->flash('success', 'Module deleted successfully');
            }else{
                session()->flash('success', 'Module deleted failed');
            }

            return redirect()->back();
        }else{
            return $this->unauthorized_access_blocked();
        }
    }
}
