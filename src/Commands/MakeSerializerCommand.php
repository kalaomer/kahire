<?php

namespace Kahire\Commands;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeSerializerCommand extends Command {

    use AppNamespaceDetectorTrait;

    protected $name = "api:serializer";

    protected $description = "Generate a new serializer";

    protected $files;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->files = $filesystem;
    }


    public function fire()
    {
        $this->makeSerializer();
    }

    protected function makeSerializer()
    {
        $name = $this->argument("name");

        if($this->files->exists($path = $this->getSerializerPath($name)))
        {
            return $this->error("Serializer already exists");
        }

        $this->makeDirectory();

        $this->files->put($path, $this->compileSerializerStub());

        $this->info("Serializer created successfully.");
    }


    protected function makeDirectory()
    {
        var_dump($this->getSerializerPath());
        if (! $this->files->isDirectory($this->getSerializerPath()))
        {
            $this->files->makeDirectory($this->getSerializerPath(), 0777);
        }
    }

    public function getSerializerPath($name=null)
    {
        $base = app_path() . DIRECTORY_SEPARATOR . "Http" . DIRECTORY_SEPARATOR . "Serializers" . DIRECTORY_SEPARATOR;

        return $name? $base . $this->getStubClassName() . ".php" : $base;
    }

    protected function compileSerializerStub()
    {
        $stub = $this->files->get(__DIR__ . '/./stubs/serializer.stub');

        $content = $this->replaceStubContent($stub);

        return $content;
    }

    protected function replaceStubContent($stub)
    {
        $replacements = [
            "{{class}}" => $this->getStubClassName(),
            "{{fields}}" => $this->getStubFields(),
            "{{namespace}}" => "FooNameSpace"
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);

        return $content;
    }

    protected function getStubClassName()
    {
        return ucfirst(camel_case($this->argument("name"))) . "Serializer";
    }

    protected function getStubFields()
    {
        $fields = $this->option("field");

        if ($fields == [])
        {
            return  "[]";
        }

        return "[]";
    }

    protected function getOptions()
    {
        return [
            ['field', 'f', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Fields for the migration'],
            ['path', null, InputOption::VALUE_OPTIONAL, 'Where should the file be created?']
        ];
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The Serializer name']
        ];
    }
}