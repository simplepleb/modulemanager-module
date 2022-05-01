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
use Menu;

use Symfony\Component\Console\Output\BufferedOutput;


class ModulemanagerController extends Controller
{

    private $protected_modules = [];

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

        $this->protected_modules = ['Article','Comment','Tag','Thememanager','Modulemanager','VirtualWallet'];
    }

    /**
     * Create Menu for Module
     */
    public static function generateModuleMenu()
    {

        Menu::modify('super_admin', function ($menu) {
            if (\Auth::user()->can('edit_settings')) {

                $menu->add([
                    'url' => route('backend.modulemanager.index'),
                    'title' => __('Feature Manager'),
                    'icon' => 'ni ni-briefcase-24 text-primary'
                ])/*->order(2)*/
                ;

            }

        });

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
        $in_active = array();

        $enabled = \Module::allEnabled();
        foreach( $enabled as $an){
            $active[] = $an->getName();
        }
        // dd( $active );

        $protected_modules = $this->protected_modules;

        $disabled = \Module::allDisabled();
        foreach( $disabled as $an){
            $in_active[] = $an->getName();
        }

        return view(
            "modulemanager::backend.index",
            compact('module_title', 'module_name', "$module_name", 'module_icon', 'module_name_singular', 'module_action','mmodules', 'in_active', 'active','protected_modules')
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


    public static function arrayStripTags($array)
    {
        $result = array();

        foreach ($array as $key => $value) {
            // Don't allow tags on key either, maybe useful for dynamic forms.
            $key = strip_tags($key);

            // If the value is an array, we will just recurse back into the
            // function to keep stripping the tags out of the array,
            // otherwise we will set the stripped value.
            if (is_array($value)) {
                $result[$key] = static::arrayStripTags($value);
            } else {
                // I am using strip_tags(), you may use htmlentities(),
                // also I am doing trim() here, you may remove it, if you wish.
                $result[$key] = trim(strip_tags($value));
            }
        }

        return $result;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {

        $input = $request->except(['_token', '_method']);

        // Because the config is auto-loaded - we need to make sure there are no hacks (Xss/html)
        $input = self::arrayStripTags($input);

        $output = '
        <?php
            return [

            ';

        foreach( $input as $key => $value){
            $output .= "'$key' => '$value',
            ";
        }
        $output .= '

        ];';

        dd( $output );
       dd( $request->except(['_token', '_method']) );
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
                if( file_exists($path.$name.'/module.json')){
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
                    // $mmodule = MModule::where('slug', $module_setings->alias)->first();
                    // if( !$mmodule ) {
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
                    // }
                }
                else
                    return redirect()->back()->with('error', __('File Missing.'));

            }
        }

        // Flash::success("<i class='fas fa-check'></i> Modules Refreshed")->important();
        return redirect()->back()->with('success', __('Module Files Refreshed.'));

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
        return redirect()->back()->with('success', $suc_msg);

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
        return redirect()->back()->with('success', 'Module Is Now Disabled');
        // Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".Auth::user()->name.'(ID:'.Auth::user()->id.')');
        return redirect()->back()->with('success', $suc_msg);

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

        return redirect()->back()->with('success', 'Module Is Now Enabled');

    }

    public function deleteModule($module_name, Request $request) {

        $msg = null;
        $suc_msg = null;
        $module = \Module::find($module_name);

        /** @var  $protected List of Modules we do not want to allow deletion */
        // $protected = ['Article','Comment','Tag','Thememanager','Modulemanager','Cryptocurrencies'];

        if( in_array($module_name, $this->protected_modules) ) {

            //Flash::error("<i class='fas fa-stop'></i> Cannot Delete Protected Module")->important();
            // return redirect("admin/modulemanager");

            $success = false;
            $message = __('Cannot Delete Protected Module');

            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);

        }
        try {

            if( $module ){
                $module->delete();
                MModule::where('slug', $module_name)->delete();
                $suc_msg = __('Module Has Been Delete');

            }
            else {
                \Module::enable($module_name);
                MModule::where('slug', $module_name)->delete();
                $module->delete();
                $suc_msg = __('Deleted Disabled Module');
            }

            /*$output = new BufferedOutput;
            $returned = \Artisan::call('module:enable '.$module_name, array(), $output );
            $suc_msg = $output->fetch();*/
        }catch (Exception $e){
            $msg = $e->getMessage();
        }


        if( $msg ){
            $success = false;
            $message = $msg;
            // Flash::error("<i class='fas fa-stop'></i> $msg")->important();
        }
        else {
            $success = true;
            $message = $suc_msg;
            //Flash::success("<i class='fas fa-check'></i> $suc_msg")->important();
        }


        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
        // Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return redirect()->back()->with('success', $suc_msg);

    }

    public function settings($name){

        if ( in_array($name, $this->protected_modules)){
            // Flash::error("<i class='fas fa-stop'></i> Settings Must Be Edited Manually For Protected Module ". $name)->important();
            return redirect()->back()->with('success', __('Settings Must Be Edited Manually For Protected Module.'));
        }

        /**
         * If the module has its own settings method use it instead
         */
        if (class_exists("\Modules\\".$name."\Http\Controllers\SettingsController")) {
            $func = "\Modules\\".$name."\Http\Controllers\SettingsController::settings";
            return $func();
        }
        elseif(class_exists("\Modules\\".$name."\Http\Controllers\Backend\SettingsController")){
            $func = "\Modules\\".$name."\Http\Controllers\Backend\SettingsController::settings";
            return $func();
        }

        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Settings';

        $$module_name_singular = MModule::where('slug', $name)->first();
        //dd( $settings );

        $settings = config( strtolower($name) );
        if( $settings === null) {
            return redirect()->back()->with('error', __('Feature has no editable settings.'));
        }
        $targetModule = label_case($name);
        /*foreach($settings as $key => $value ){
            dd( $key );
        }
        dd( config( strtolower($module_name) ));*/

        // Log::info(label_case($module_title.' '.$module_action).' | User:'.Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return view(
            "modulemanager::backend.settings",
            compact( 'module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action',
                "$module_name_singular", 'settings', 'targetModule')
        );
    }
}
