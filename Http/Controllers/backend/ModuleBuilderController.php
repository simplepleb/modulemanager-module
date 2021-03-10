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
use Modules\Modulemanager\Entities\MModule;
use Symfony\Component\Console\Output\BufferedOutput;


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
        $msg = null;
        $suc_msg = null;
        $output = new BufferedOutput;
        $modelName = Str::studly($request->table_name);

        /**
         * Make the Module
         */
        try {

            $returned = \Artisan::call('module:make '.$request->module_name, array(), $output );
            /*$suc_msg = $output->fetch();
            dd( $suc_msg );*/

        }catch (Exception $e){

            $msg = $e->getMessage();
        }

        /**
         * Make the Model
         */
        try {
            $returned = \Artisan::call('module:make-model', array('model' => $modelName,'module' => $request->module_name,'--fillable' => 'field_one,field_two'), $output );

        }catch (Exception $e){
            $msg = $e->getMessage();
        }

        /**
         * Make the Migration
         */
        try {
            $returned = \Artisan::call('module:make-migration', array('name' => 'create_'.$request->table_name.'_table','module' => $request->module_name), $output );

        }catch (Exception $e){
            $msg = $e->getMessage();
        }

        $suc_msg = $output->fetch();
        $suc_msg = str_replace(base_path(), 'File: ',$suc_msg);

        $description = $request->module_description;
        if( !empty($description )){

            $module_filename = base_path('Modules/'.$request->module_name.'/module.json');
            $composer_filename = base_path('Modules/'.$request->module_name.'/composer.json');

            $module_json = file_get_contents($module_filename);
            $module_json = str_replace('"description": "",', '"description": "'.$description.'",',$module_json);
            file_put_contents($module_filename,$module_json);

            $composer_json = file_get_contents($composer_filename);
            $composer_json = str_replace('"description": "",', '"description": "'.$description.'",',$composer_json);
            file_put_contents($composer_filename,$composer_json);

        }

        /**
         * All finished send back the correct session message
         */
        if( $msg ){
            Flash::error("<i class='fas fa-stop'></i> $msg")->important();
        }
        else {
            Flash::success("<i class='fas fa-check'></i> $suc_msg")->important();

            $refresh = new ModulemanagerController();
            $refresh->refresh();
        }

        // Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return redirect("admin/modulemanager");
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

    public function generateModule(Request $request){

        $msg = null;
        $suc_msg = null;
        try {
            $output = new BufferedOutput;
            $returned = \Artisan::call('module:make '.$request->module_name, array(), $output );
            $suc_msg = $output->fetch();
        }catch (Exception $e){
            $msg = $e->getMessage();
        }

        if( $msg ){
            Flash::error("<i class='fas fa-stop'></i> $msg")->important();
        }
        else {
            Flash::success("<i class='fas fa-check'></i> $suc_msg")->important();
        }

        // Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return redirect("admin/modulemanager");
    }

}
