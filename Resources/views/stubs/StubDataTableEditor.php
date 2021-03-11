<?php

namespace Modules\%%moduleName%%\DataTables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Modules\%%moduleName%%\Entities\%%modelName%%;
use Yajra\DataTables\DataTablesEditor;

class %%modelName%%DataTableEditor extends DataTablesEditor
{
    protected $model = %%modelName%%::class;

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
