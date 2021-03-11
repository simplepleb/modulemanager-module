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

    public function rest($n)
    {
        clearstatcache();
        ob_flush();
        flush();
        return
            sleep($n);
    }
    public static function makeMigration($moduleName,$fields, $tableName){

        $output = new BufferedOutput;
        $result = substr_compare($tableName, 's', -1, 1);
        if( $result !== 0) {
            $tableName = $tableName.'s';
        }
        /**
         * Make the Migration
         */
        try {
            $returned = \Artisan::call('module:make-migration', array('name' => 'create_'.$tableName.'_table','module' => $moduleName), $output );

        }catch (Exception $e){
            $msg = $e->getMessage();
        }

        $suc_msg = $output->fetch();
        $suc_msg = str_replace('Created : ', '',$suc_msg);

        $suc_msg = trim($suc_msg);
        sleep(1);
        $m_contents = file_get_contents($suc_msg);

        $new_fields = '$table->id();
        ';
        foreach ($fields as $field => $val ) {

            if( $val['type'] && $val['type'] != NULL ) {

                $size = NULL;
                if ( $val['size'] ) $size = $val['size'];
                if ( $val['nullable'] ) $nullable = true;

                $new_fields .= self::generateTableDef($val['type'], $val['name'], $size, $nullable).'
                    ';
            }
        }

        //$new_fields = '';
        $m_contents = str_replace('$table->id();', $new_fields, $m_contents);
        $mig_file = fopen($suc_msg, 'w');
        fwrite($mig_file, $m_contents);
        fclose($mig_file);

        return true;

    }

    public static function generateTableDef($type, $name, $size = NULL, $nullable = false ){

        $migr = null;
        switch ($type) {
            case "char":
            case "string":
                $migr = "table->string('" . strtolower($name) . "');";
                break;
            case "text":
                $migr = "table->text('" . strtolower($name) . "');";
                break;
            case "boolean":
                $migr = "table->boolean('" . strtolower($name) . "');";
                break;
            case "uuid":
                $migr = "table->uuid('" . strtolower($name) . "');";
                break;
            case "integer":
                $migr = "table->integer('" . strtolower($name) . "');";
                break;
            case "smallInteger":
                $migr = "table->smallInteger('" . strtolower($name) . "');";
                break;
            case "json":
                $migr = "table->json('" . strtolower($name) . "');";
                break;
            case "longText":
                $migr = "table->longText('" . strtolower($name) . "');";
                break;
            case "mediumText":
                $migr = "table->mediumText('" . strtolower($name) . "');";
                break;

        }


        if( !empty($migr)) {
            if( $nullable && $size > 0 ){
                $migr = str_replace("');","', $size)->nullable();",$migr);
            }
            else {
                if( $nullable )
                    $migr = str_replace(";","->nullable();",$migr);
                if( $size > 0 )
                    $migr = str_replace("');","', $size);",$migr);
            }

            $migr = str_replace('table','$table',$migr);
        }


        return $migr;

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

        $fillable = $request->field_name;
        $str_fillable = implode (",", $fillable);

        $fields = array();
        $field_names = $request->field_name;
        $field_types = $request->field_type;
        $field_fillables  = $request->field_fillable;
        $field_nullables  = $request->field_nullable;
        $field_datatables  = $request->field_datatable;
        $field_defaults  = $request->field_default;
        $field_sizes  = $request->field_size;

        foreach( $request->field_name as $index => $value ){
            //
            $fields[$index]['name'] = $value;

            if( $field_types[$index] )
               $fields[$index]['type'] = $field_types[$index];

            if( isset($field_sizes[$index]) ){
                $fields[$index]['size'] = $field_sizes[$index];
            } else { $fields[$index]['size'] = 'NULL'; }

            if( isset($field_nullables[$index]) ){
                $fields[$index]['nullable'] = $field_nullables[$index];
            } else { $fields[$index]['nullable'] = 'NULL'; }

            if( isset($field_fillables[$index]) ){
                $fields[$index]['fillable'] = $field_fillables[$index];
            } else { $fields[$index]['fillable'] = 'NULL'; }

            if( isset($field_datatables[$index]) ){
                $fields[$index]['datatable'] = $field_datatables[$index];
            } else { $fields[$index]['datatable'] = 'NULL'; }

            if( isset($field_defaults[$index]) ){
                $fields[$index]['default'] = $field_defaults[$index];
            } else { $fields[$index]['default'] = 'NULL'; }


        } // End field name loop


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
            $returned = \Artisan::call('module:make-model', array('model' => $modelName,'module' => $request->module_name,'--fillable' => $str_fillable), $output );

        }catch (Exception $e){
            $msg = $e->getMessage();
        }

        /**
         * Make the Migration
         */
        self::makeMigration($request->module_name,$fields,$request->table_name );

        $suc_msg = $output->fetch();
        $suc_msg = str_replace(base_path(), 'File: ',$suc_msg);

        /**
         * Modify the default module.json and compose.json
         */
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

            /**
             * If we're all good - add the module to the db
             */
            $refresh = new ModulemanagerController();
            $refresh->refresh();

            self::generateViewFiles($request->module_name, $modelName, $fillable);
        }

        Log::info(label_case($request->module_name. ' Module Created ')." | ' by User:".Auth::user()->name.'(ID:'.Auth::user()->id.')');

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

    public static function migrateModule($moduleName)
    {
        $msg = null;
        $suc_msg = null;
        try {
            $output = new BufferedOutput;
            $returned = \Artisan::call('module:migrate '.$moduleName, array(), $output );
            $suc_msg = $output->fetch(); // php artisan module:migrate
        }catch (Exception $e){
            $msg = $e->getMessage();
        }

        if( $msg ){
            Flash::error("<i class='fas fa-stop'></i> $msg")->important();
        }
        else {
            Flash::success("<i class='fas fa-check'></i> $suc_msg")->important();
        }
    }

    public static function generateViewFiles($moduleName, $modelName, $fillable){

        $c_path = base_path('Modules/'.$moduleName.'/Resources/views/');

        /**
         * Create the Index (list) view
         */
        $indexView = fopen($c_path.'index.blade.php','w');
        $indexStub = base_path('Modules/Modulemanager/Resources/views/stubs/index.blade.php');

        $indexContents = file_get_contents($indexStub);

        $fields = '';
        foreach ($fillable as $field){
            $fields .= "<th>".ucwords(str_replace('_',' ', $field))."</th>";
        }
        $indexContents = str_replace('%%field_headers%%', $fields, $indexContents);

        foreach ($fillable as $item) {
            $json_fields[] = ['label' => ucwords(str_replace('_',' ', $item)).':', 'name' => $item ];
        }
        $indexContents = str_replace('%%json_fields%%', json_encode($json_fields), $indexContents);

        foreach ($fillable as $item) {
            $json_columns[] = ['data' => $item, 'name' => ucfirst(str_replace('_',' ', $item))];
        }

        $indexContents = str_replace('%%json_columns%%', json_encode($json_columns), $indexContents);
        $indexContents = str_replace('%%moduleName%%', $moduleName, $indexContents);
        $indexContents = str_replace('%%moduleNameLower%%', strtolower($moduleName), $indexContents);
        fwrite($indexView, $indexContents);
        fclose($indexView);
        /** Finish Index View */

        /**
         * Start The create blade file (not used but needs to be in the view folder to DTables)
         */
        $createView = fopen($c_path.'create.blade.php','w');
        $createStub = base_path('Modules/Modulemanager/Resources/views/stubs/create.blade.php');

        $createContents = file_get_contents($createStub);
        fwrite($createView, $createContents);
        fclose($createView);
        /** End 'create' blade file */

        /**
         * Start The show blade file (not used but needs to be in the view folder to DTables)
         */
        $showView = fopen($c_path.'show.blade.php','w');
        $showStub = base_path('Modules/Modulemanager/Resources/views/stubs/show.blade.php');

        $showContents = file_get_contents($showStub);
        fwrite($showView, $showContents);
        fclose($showView);
        /** End 'show' blade file */

        /** Start Routes append */
        ob_start();
        ?>
        < ? php

        Route::prefix('<?php echo strtolower($moduleName) ?>')->group(function() {
            Route::get('/', '<?php echo $moduleName ?>Controller@index');
        });
        Route::get("<?php echo strtolower($moduleName) ?>/dtable", ['as' => "<?php echo strtolower($moduleName) ?>.dtable", 'uses' => "\Modules\<?php echo $moduleName ?>\Http\Controllers\<?php echo $moduleName ?>Controller@dtable"]);
        Route::post("<?php echo strtolower($moduleName) ?>/editor", function(\Modules\<?php echo $moduleName ?>\DataTables\<?php echo $modelName ?>DataTableEditor $editor) {
            return $editor->process(request());
        });
        <?php

        $routes = ob_get_clean();
        $routes = str_replace('< ? php','<?php', $routes);
        $routesPath = base_path('Modules/'.$moduleName.'/Routes/web.php');
        $routesFile = fopen($routesPath, 'w') or die('Cannot open file: '.$routesPath);

        fwrite($routesFile, $routes);
        fclose($routesFile);
        /** End Routes File */

        /**
         * Start the DataTables File
         */
        $path = base_path('Modules/'.$moduleName.'/DataTables');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $dataClassFile = base_path('Modules/'.$moduleName.'/DataTables/'.$modelName.'DataTableEditor.php');
        $dtStub = base_path('Modules/Modulemanager/Resources/views/stubs/StubDataTableEditor.php');

        $dtContents = file_get_contents($dtStub);
        $dtContents = str_replace('%%moduleName%%', $moduleName, $dtContents);
        $dtContents = str_replace('%%modelName%%', $modelName, $dtContents);

        $dtFile = fopen($dataClassFile, 'w');
        fwrite($dtFile, $dtContents);
        /** End DataTable File */

        // public function index()
        $controllerPath = base_path('Modules/'.$moduleName.'/Http/Controllers/'.$moduleName.'Controller.php');
        $controllerContents = file_get_contents($controllerPath);

        $c_file = fopen($controllerPath, 'w');
        $controllerContents = str_replace('public function index()', 'public function indexOld()', $controllerContents);
        $controllerContents = str_replace('use Illuminate\Routing\Controller;', 'use Illuminate\Routing\Controller;
        use DataTables;', $controllerContents);
        $controllerContents = str_replace('public function destroy($id)
    {
        //
    }
}', '', $controllerContents);

        ob_start() ?>
        public function index()
            {
                $module_title = '<?php echo ucfirst($moduleName) ?>';
                $module_name = '<?php echo ucfirst($moduleName) ?>';
                $module_path = '<?php echo strtolower($moduleName) ?>';
                $module_icon = 'fas fa-file-alt';
                $module_model = "\Modules\<?php echo $moduleName ?>\Entities\<?php echo $modelName ?>";
                $module_name_singular = "<?php echo Str::singular($moduleName) ?>";

                $module_action = 'List';

                $$module_name = $module_model::paginate();

                return view(
                "<?php echo strtolower($moduleName) ?>::index",
                compact('module_title', 'module_name', "$module_name", 'module_icon', 'module_name_singular', 'module_action')
                );
            }

        public function dtable(){

                $items = \Modules\<?php echo $moduleName ?>\Entities\<?php echo $modelName ?>::all();

                return DataTables::of($items)->addIndexColumn()->toJson();


            }

        }

        <?php
        $new_index = ob_get_clean();

        $controllerContents = rtrim($controllerContents ,"}");
        $controllerContents = $controllerContents. '
        '. $new_index;

        fwrite($c_file, $controllerContents);
        fclose($c_file);

        self::migrateModule($moduleName);



    }

}
