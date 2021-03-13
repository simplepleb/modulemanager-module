<?php

namespace Modules\MODULE_NAME\DataTables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Modules\MODULE_NAME\Entities\MODEL_NAME;
use Yajra\DataTables\DataTablesEditor;

class MODULE_NAMEDataTableEditor extends DataTablesEditor
{
    protected $model = MODEL_NAME::class;

    /**
     * Get create action validation rules.
     *
     * @return array
     */
    public function createRules()
    {
        return [

        ];
    }

    /**
     * Get edit action validation rules.
     *
     * @param Model $model
     * @return array
     */
    public function editRules(Model $model)
    {
        return [

        ];
    }

    /**
     * Get remove action validation rules.
     *
     * @param Model $model
     * @return array
     */
    public function removeRules(Model $model)
    {
        return [];
    }


}
