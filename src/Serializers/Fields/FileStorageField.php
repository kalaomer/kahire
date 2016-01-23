<?php namespace Kahire\Serializers\Fields;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class FileStorageField extends FileField {

    /**
     * @var string
     */
    protected $disk = "local";

    /**
     * @var string
     */
    protected $subDir;


    /**
     * @param null $value
     *
     * @return $this|string
     */
    public function disk($value = null)
    {
        if ( $value !== null )
        {
            if ( ! is_string($value) )
            {
                throw new \InvalidArgumentException("disk must be string.");
            }

            $this->disk = $value;

            return $this;
        }

        return $this->disk;
    }


    public function subDir()
    {
        if ( func_num_args() > 0 )
        {
            $value = func_get_arg(1);

            if ( ! is_string($value) )
            {
                throw new \InvalidArgumentException("subDir must be string.");
            }

            $this->subDir = $value;

            return $this;
        }

        return $this->subDir;
    }


    /**
     * After Validation save file.
     *
     * @param $value
     *
     * @return string
     */
    public function runValidation($value)
    {
        /* @var $file File */
        $file = parent::runValidation($value);

        return $this->uploadFile($file);
    }


    protected function uploadFile(File $file)
    {
        $name = $file->getFilename();

        $path = $this->getUniqueFilePath($name);

        $this->getDisk()->put($path, file_get_contents($file->getRealPath()));

        return $path;
    }


    /**
     * @return FileSystem
     */
    protected function getDisk()
    {
        return Storage::disk($this->disk);
    }


    /**
     * @param $name
     *
     * @return string
     */
    protected function getUniqueFilePath($name)
    {
        $basePath = $this->subDir ? $this->subDir . DIRECTORY_SEPARATOR . $name : $name;

        $path = $basePath;

        while (true)
        {
            if ( ! $this->getDisk()->exists($path) )
            {
                break;
            }

            $path = uniqid($basePath);
        }

        return $path;
    }

}