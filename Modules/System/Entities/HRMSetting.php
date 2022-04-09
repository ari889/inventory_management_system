<?php

namespace Modules\System\Entities;

use Modules\Base\Entities\BaseModel;

class HRMSetting extends BaseModel
{
    protected $table = 'hrm_settings';
    protected $fillable = ['check_in', 'check_out', 'created_by', 'updated_by'];
}
