<?php

namespace Kahire\Serializers\Fields;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use InvalidArgumentException;
use Kahire\Serializers\Fields\Attributes\MaximumAttribute;
use Kahire\Serializers\Fields\Attributes\MimesAttribute;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class FileField.
 * @method $this disk(str $value)
 * @method $this visibility(str $value)
 * @method $this urlPrefix(str $value)
 * @method $this subDir(str $value)
 */
class FileField extends Field
{
    use MaximumAttribute, MimesAttribute;

    /**
     * @var string
     */
    protected $disk;

    /**
     * @var string
     */
    protected $subDir;

    /**
     * @var string
     */
    protected $urlPrefix = '/';

    /**
     * @var string
     */
    protected $visibility = null;

    /**
     * FileField constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->disk = Config::get('filesystems.default');
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

    /**
     * @param $value
     *
     * @return File
     * @throws Exceptions\ValidationError
     */
    public function toInternalValue($value)
    {
        if (! $value instanceof File) {
            $this->fail('invalid');
        }

        return $value;
    }

    /**
     * @param string $filePath
     *
     * @return mixed
     */
    public function toRepresentation($filePath)
    {
        return $this->getFileURL($filePath);
    }

    /**
     * @param $filePath
     *
     * @return string
     */
    public function getFileURL($filePath)
    {
        $driver = Config::get("filesystems.disks.{$this->disk}.driver");

        switch ($driver) {

            case 's3':
                return $this->getDisk()->getDriver()->getAdapter()->getClient()->getObjectUrl(Config::get('filesystems.disks.s3.bucket'),
                    $filePath);

            case 'local':
                return URL::to($this->urlPrefix.$filePath);

        }

        throw new InvalidArgumentException('Disk is not supported.');
    }

    /**
     * @param File $file
     *
     * @return string
     */
    protected function uploadFile(File $file)
    {
        $name = $file->getFilename();

        $path = $this->getUniqueFilePath($name);

        $this->getDisk()->put($path, file_get_contents($file->getRealPath()), $this->visibility);

        return $path;
    }

    protected function getDiskAttribute()
    {
        return $this->disk;
    }

    protected function setDiskAttribute($value)
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException('disk must be string.');
        }

        $this->disk = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function useCloud()
    {
        $this->disk(Config::get('filesystems.cloud'));

        return $this;
    }

    protected function getUrlPrefixAttribute()
    {
        return $this->urlPrefix;
    }

    protected function setUrlPrefixAttribute($value)
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException('urlPrefix must be string.');
        }

        $value = trim($value, '/').'/';

        $this->urlPrefix = $value;

        return $this;
    }

    protected function getSubDirAttribute()
    {
        return $this->subDir;
    }

    protected function setSubDirAttribute($value)
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException('subDir must be string.');
        }

        $this->subDir = $value;

        return $this;
    }

    protected function getVisibilityAttribute()
    {
        return $this->visibility;
    }

    protected function setVisibilityAttribute($value)
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException('publicUpload must be string.');
        }

        $this->visibility = $value;

        return $this;
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
        $basePath = $this->subDir ? $this->subDir.DIRECTORY_SEPARATOR.$name : $name;

        $path = $basePath;
        $pathInfo = pathinfo($basePath);

        while (true) {
            if (! $this->getDisk()->exists($path)) {
                break;
            }

            $path = $pathInfo['dirname'].DIRECTORY_SEPARATOR.$pathInfo['filename'].uniqid().'.'.$pathInfo['extension'];
        }

        return $path;
    }
}
