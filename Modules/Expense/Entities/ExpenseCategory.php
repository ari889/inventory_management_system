<?php

namespace Modules\Expense\Entities;

use Modules\Base\Entities\BaseModel;

class ExpenseCategory extends BaseModel
{
    protected $fillable = ['name', 'status', 'created_by', 'updated_by'];

    protected $cat_name;

    public function setName($cat_name){
        $this->cat_name = $cat_name;
    }

    private function get_datatable_query()
    {
        if(permission('expense-category-bulk-delete')){
            $this->column_order = [null, 'id', 'name', 'status', null];
        }else{
            $this->column_order = ['id', 'name', 'status', null];
        }

        $query = self::toBase();

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->cat_name)) {
            $query->where('name', 'like', '%' . $this->cat_name . '%');
        }
        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthValue != -1) {
            $query->offset($this->startValue)->limit($this->lengthValue);
        }
        return $query->get();
    }

    public function count_filtered()
    {
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }

    public function count_all()
    {
        return self::toBase()->get()->count();
    }
}
