<?php

namespace Modules\ModuleManager\Http\Controllers\Backend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\ModuleManager\Entities\MModule;
use Modules\Thememanager\Entities\SiteTheme;

class ModuleManagerController extends Controller
{

    public function __construct()
    {
        // Page Title
        $this->module_title = 'ModuleManager';

        // module name
        $this->module_name = 'modulemanager';

        // directory path of the module
        $this->module_path = 'modulemanager';

        // module icon
        $this->module_icon = 'fas fa-file-alt';

        // module model name, path
        $this->module_model = "Modules\ModuleManager\Entities\MModule";
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('modulemanager::index');
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

                $module_status = file_get_contents( base_path('modules_statuses') );
                $module_status = '['.$module_status.']';
                $vals = json_decode($module_status);
                $module_status = $vals[0];

                // dd( $vals->slug );
                $mmodule = MModule::where('slug', $module_setings->slug)->first();
                if( !$mmodule ) {
                    $mmodule  = MModule::updateOrCreate(
                        [
                            'slug' => $module_setings->slug
                        ],
                        [
                            'name' => $module_setings->name,
                            'settings' => json_encode($module_setings),
                            'active' => 0
                        ]
                    );
                }

                // dd( $theme );
            }

            // dd( $name );
            //echo "filename: $file : filetype: " . filetype($file) . "<br />";

        }

        Flash::success("<i class='fas fa-check'></i> Modules Refreshed")->important();

        // Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return redirect("admin/$module_name");
    }

}
