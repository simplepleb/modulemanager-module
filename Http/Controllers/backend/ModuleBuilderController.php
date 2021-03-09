<?php

namespace Modules\Modulemanager\Http\Controllers\Backend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;
use Flash;
use Log;


class ModuleBuilderController extends Controller
{

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Modulemanager';

        // module name
        $this->module_name = 'modulemanager';

        // directory path of the module
        $this->module_path = 'modulemanager';

        // module icon
        $this->module_icon = 'fas fa-file-alt';

        // module model name, path
        $this->module_model = "Modules\Modulemanager\Entities\MModule";
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Build';

        Log::info(label_case($module_title.' '.$module_action).' | User:'.Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return view(
            "modulemanager::backend.builder.create",
            compact('module_title', 'module_name', 'module_icon', 'module_action', 'module_name_singular'/*, 'categories'*/)
        );

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('modulemanager::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('modulemanager::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }


}
