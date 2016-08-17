<?php

namespace Kahire\Commands;

use Kahire\Commands\Parsers\FieldParser;

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

    protected $fieldParser;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->files = $filesystem;
        $this->fieldParser = new FieldParser();
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
        if (! $this->files->isDirectory($this->getSerializerPath()))
        {
            $this->files->makeDirectory($this->getSerializerPath(), 0777);
        }
    }

    public function getSerializerPath($name=null)
    {
        $folderPath = app_path("Http/Serializers");

        if ($name === null) {
            return $folderPath;
        }

        return $folderPath . DIRECTORY_SEPARATOR . $this->getStubClassName($name) . ".php";
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

    protected function getStubClassName($name = null)
    {
        return studly_case($name ? $name : $this->argument("name")) . "Serializer";
    }

    protected function getStubFields()
    {
        $fields = $this->argument("fields");

        $parsedFields = $this->fieldParser->parse($fields);

        if ($fields == [])
        {
            return  "[]";
        }

        return "[]";
    }

    protected function getOptions()
    {
        return [
            ['path', null, InputOption::VALUE_OPTIONAL, 'Where should the file be created?']
        ];
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The Serializer name'],
            ['fields', InputArgument::REQUIRED, 'The Serializer fields']
        ];
    }
}