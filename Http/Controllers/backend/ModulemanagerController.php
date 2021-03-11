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
use Modules\Modulemanager\Entities\CryptoCurrencies;
use Modules\Modulemanager\Entities\MModule;

use Symfony\Component\Console\Output\BufferedOutput;


class ModulemanagerController extends Controller
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
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $$module_name = MModule::paginate();
        $mmodules = $module_model::get();

        $active = array();
        $enabled = \Module::allEnabled();
        foreach( $enabled as $an){
            $active[] = $an->getName();
        }
        // dd( $active );

        $disabled = \Module::allDisabled();
        foreach( $disabled as $an){
            $in_active[] = $an->getName();
        }

        return view(
            "modulemanager::backend.index",
            compact('module_title', 'module_name', "$module_name", 'module_icon', 'module_name_singular', 'module_action','mmodules', 'in_active', 'active')
        );
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('modulemanager::create');
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

    /**
     * Refreshes the module settings and ensures every module (folder) is in the DB
     *
     * @author SimplePleb
     * @since v1.0
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function refresh()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';

        $dir = base_path('Modules/').'*';
        $path = base_path().'/Modules/';

        // Open a known directory, and proceed to read its contents
        foreach(glob($dir) as $file)
        {
            // dd(filetype($file) );
            if( filetype($file) == 'dir'){
                $name = str_replace($path,'',$file);
                // Theme Settings
                $settings = file_get_contents($path.$name.'/module.json');
                $settings = preg_replace( "/\r|\n/", "", $settings );
                $settings = '['.$settings.']';
                $vals = json_decode($settings);

                $module_setings = $vals[0];
                unset($vals);

                $module_status = file_get_contents( base_path('modules_statuses.json') );
                $module_status = '['.$module_status.']';
                $vals = json_decode($module_status);
                $module_status = $vals[0];

                // dd( $vals->slug );
                $mmodule = MModule::where('slug', $module_setings->alias)->first();
                if( !$mmodule ) {
                    $mmodule  = MModule::updateOrCreate(
                        [
                            'slug' => $module_setings->alias
                        ],
                        [
                            'name' => $module_setings->name,
                            'settings' => '['.json_encode($module_setings).']',
                            /*'active' => 0*/
                        ]
                    );
                }

            }
        }

        Flash::success("<i class='fas fa-check'></i> Modules Refreshed")->important();

        return redirect("admin/$module_name");
    }

    /**
     * Updates the module (dependencies)
     *
     * @param $module_name
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function artisanUpdate($module_name) {

        $msg = null;
        $suc_msg = null;
        try {
            $output = new BufferedOutput;
            $returned = \Artisan::call('module:update '.$module_name, array(), $output );
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

    public function disable($module_name) {

        $msg = null;
        $suc_msg = null;
        try {
            $output = new BufferedOutput;
            $returned = \Artisan::call('module:disable '.$module_name, array(), $output );
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

    public function enable($module_name) {

        $msg = null;
        $suc_msg = null;
        try {
            $output = new BufferedOutput;
            $returned = \Artisan::call('module:enable '.$module_name, array(), $output );
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

    public function deleteModule($module_name) {

        $msg = null;
        $suc_msg = null;
        $module = \Module::find($module_name);

        /** @var  $protected List of Modules we do not want to allow deletion */
        $protected = ['Article','Comment','Tag','Thememanager','Modulemanager','Cryptocurrencies'];

        if( in_array($module_name, $protected) ) {

            Flash::error("<i class='fas fa-stop'></i> Cannot Delete Protected Module")->important();
            return redirect("admin/modulemanager");
        }
        try {

            if( $module ){
                $module->delete();
                MModule::where('slug', $module_name)->delete();
                $suc_msg = 'Deleted Module';
            }
            else {
                \Module::enable($module_name);
                MModule::where('slug', $module_name)->delete();
                $module->delete();
                $suc_msg = 'Deleted Disabled Module';
            }

            /*$output = new BufferedOutput;
            $returned = \Artisan::call('module:enable '.$module_name, array(), $output );
            $suc_msg = $output->fetch();*/
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
