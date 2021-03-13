# Module Management for Laravel
A Module for managing Laravel modules settings, status etc

This package gives you everything you need to build and manage CRUD modules with ease. Fill in the builder form and the entire module is built for you. Once you click 'Build' you will be redirected to the index page of the new module and immediately be able to add and edit items to the new module. 

[![Latest Stable Version](https://poser.pugx.org/simplepleb/modulemanager-module/v)](//packagist.org/packages/simplepleb/modulemanager-module) [![Total Downloads](https://poser.pugx.org/simplepleb/modulemanager-module/downloads)](//packagist.org/packages/simplepleb/modulemanager-module) [![Latest Unstable Version](https://poser.pugx.org/simplepleb/modulemanager-module/v/unstable)](//packagist.org/packages/simplepleb/modulemanager-module) [![License](https://poser.pugx.org/simplepleb/modulemanager-module/license)](//packagist.org/packages/simplepleb/modulemanager-module)

# Module Settings 
Without requiring any database use - we created a simple way to allow the site admin to modify settings for any module.

By default, the settings form will edit and save the ``` Module\{ModuleName}\Config\config.php ``` file. If your module has a more complex settings requirement take note of the settings() method of the ModuleManager.

```php 
        /**
         * If the module has its own settings method use it instead
         */
        if (class_exists("\Modules\\".$name."\Http\Controllers\SettingsController")) {

            $func = "\Modules\\".$name."\Http\Controllers\SettingsController::settings";

            return $func();
        }
```

If the module does not have its own settings method the ModuleManager default will display the form.

# Dashboard Screenshot

![Screen Shot 2021-03-10 at 6 40 58 AM](https://user-images.githubusercontent.com/79759974/110624073-bb5f4980-816b-11eb-98bb-cfc0481c295c.png)


# Builder Screenshot

![Screen Shot 2021-03-11 at 8 17 51 AM](https://user-images.githubusercontent.com/79759974/110793210-5a567500-8242-11eb-8849-68e0bc033a57.png)

This module manager makes full use of the great [Module Package by Nwidart](https://nwidart.com/laravel-modules/v6/introduction)

