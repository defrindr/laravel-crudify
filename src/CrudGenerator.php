<?php

namespace Defrindr\Crudify;

use Illuminate\Console\Command;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crudify:gen 
        {name : Class (singular) for example User}
        {--m|module=}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD operations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $module = ucwords($this->option('module'));
        if (!$module) {
            $this->components->error('Module must be set');
            exit(0);
        }

        $this->components->info("$module");

        $this->model($name, $module);
        $this->request($name . 'Store', $module);
        $this->request($name . 'Update', $module);
        $this->controller($name, $module);
        $this->service($name, $module);
        $this->resource($name, $module);

        // File::append(base_path('routes/api.php'), 'Route::resource(\'' . Str::plural(strtolower($name)) . "', '{$name}Controller');");
        $this->components->info("$name successfully created");
    }

    protected function model($name, $module = null)
    {
        $namespace = $this->getNamespaceModel($module);
        $modelTemplate = str_replace(
            [
                '{{ rootNamespace }}',
                '{{ namespace }}',
                '{{ class }}',
            ],
            [
                $this->rootNamespace(),
                $namespace,
                $name,
            ],
            $this->getStub('Model')
        );

        $path = $module ? "/Models/{$module}/" : '/Models';
        if (!file_exists(app_path($path))) {
            mkdir(app_path($path), 0777, true);
        }

        $this->create(app_path("{$path}/{$name}.php"), $modelTemplate);
    }

    protected function controller(string $name, $module = null): void
    {
        $namespace = $this->getNamespace('Controllers', $module);

        $storeRequestClass = $name . 'StoreRequest';
        $updateRequestClass = $name . 'UpdateRequest';
        $serviceClass = $name . 'Service';
        $class = $name . 'Controller';

        $storeRequestNamespace = $this->getNamespace('Requests', $module) . '\\' . $storeRequestClass;
        $updateRequestNamespace = $this->getNamespace('Requests', $module) . '\\' . $updateRequestClass;
        $serviceNamespace = $this->getNamespace('Services', $module) . '\\' . $serviceClass;

        $controllerTemplate = str_replace(
            [
                '{{ rootNamespace }}',
                '{{ namespace }}',
                '{{ class }}',
                '{{ storeRequestNamespace }}',
                '{{ updateRequestNamespace }}',
                '{{ serviceNamespace }}',
                '{{ storeRequestClass }}',
                '{{ updateRequestClass }}',
                '{{ serviceClass }}',
            ],
            [
                $this->rootNamespace(),
                $namespace,
                $class,
                $storeRequestNamespace,
                $updateRequestNamespace,
                $serviceNamespace,
                $storeRequestClass,
                $updateRequestClass,
                $serviceClass,
            ],
            $this->getStub('Controller')
        );

        $path = $this->getPath('Controllers', $module);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $this->create($path . "/{$name}Controller.php", $controllerTemplate);
    }

    protected function service(string $name, $module = null): void
    {
        $namespace = $this->getNamespace('Services', $module);

        $class = $name . 'Service';
        $modelClass = $name;
        $resourceClass = $name . 'Resource';

        $resourceNamespace = $this->getNamespace('Resources', $module) . '\\' . $resourceClass;
        $ModelNamespace = $this->getNamespaceModel($module) . '\\' . $modelClass;

        $serviceTemplate = str_replace(
            [
                '{{ rootNamespace }}',
                '{{ namespace }}',
                '{{ class }}',
                '{{ modelNamespace }}',
                '{{ modelClass }}',
                '{{ resourceNamespace }}',
                '{{ resourceClass }}',
            ],
            [
                $this->rootNamespace(),
                $namespace,
                $class,
                $ModelNamespace,
                $name,
                $resourceNamespace,
                $resourceClass,
            ],
            $this->getStub('Service')
        );

        $path = $this->getPath('Services', $module);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $this->create($path . "/{$name}Service.php", $serviceTemplate);
    }

    protected function request(string $name, $module = null): void
    {
        $namespace = $this->getNamespace('Requests', $module);
        $requestTemplate = str_replace(

            [
                '{{ rootNamespace }}',
                '{{ namespace }}',
                '{{ class }}',
            ],
            [
                $this->rootNamespace(),
                $namespace,
                $name . 'Request',
            ],
            $this->getStub('Request')
        );

        $path = $this->getPath('Requests', $module);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $this->create("$path/{$name}Request.php", $requestTemplate);
    }

    protected function resource(string $name, $module = null): void
    {
        $namespace = $this->getNamespace('Resources', $module);
        $resourceTemplate = str_replace(

            [
                '{{ rootNamespace }}',
                '{{ namespace }}',
                '{{ class }}',
            ],
            [
                $this->rootNamespace(),
                $namespace,
                $name . 'Resource',
            ],
            $this->getStub('Resource')
        );

        $path = $this->getPath('Resources', $module);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $this->create("$path/{$name}Resource.php", $resourceTemplate);
    }

    protected function getStub(string $type)
    {
        return file_get_contents($this->resolveStubPath("stubs/crudify/$type.stub"));
    }

    protected function getNamespace(string $type, $module = null)
    {
        $namespace = $this->rootNamespace() . 'Http\\' . ucwords($type);
        if ($module) {
            $namespace = $this->rootNamespace() . 'Modules\\' . $module . '\\' . ucwords($type);
        }

        return $namespace;
    }

    protected function getNamespaceModel($module = null)
    {
        $namespace = $this->rootNamespace() . 'Models';
        if ($module) {
            $namespace = $this->rootNamespace() . 'Models\\' . $module;
        }

        return $namespace;
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        $path = file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . "/../". $stub;

        return $path;
    }

    protected function getPath(string $name, $module = null)
    {
        $name = ucwords($name);
        $module = ucwords($module);

        return $module ? app_path('/Modules/' . $module . '/' . $name)
            : app_path('/Http/' . $name);
    }

    /**
     * Mendapatkan root namespace
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }

    protected function create($path, $content)
    {
        if (file_exists($path)) {
            if ($this->confirm('Do you need overwrite ' . $path)) {
                $this->components->info($path . ' overwritted');
                file_put_contents($path, $content);
            }
        } else {
            file_put_contents($path, $content);
        }
    }
}
