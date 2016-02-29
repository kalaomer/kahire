<?php

namespace Kahire\Tests\Serializers\Fields;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Kahire\Serializers\Fields\Exceptions\ValidationError;
use Kahire\Serializers\Fields\FileField;
use Symfony\Component\HttpFoundation\File\File;

class FileFieldTest extends FieldTestCase
{
    public $fieldClass = FileField::class;

    /**
     * @var File
     */
    public $file;

    /**
     * @var FileField
     */
    public $field;

    public function setUp()
    {
        parent::setUp();

        Storage::put('tmp/foo.txt', 'Hi!');
        $this->file = new File(storage_path('app/tmp/foo.txt'));
    }

    public function tearDown()
    {
        Storage::delete('tmp/foo.txt');

        parent::tearDown();
    }

    public function testFileInput()
    {
        $this->assertEquals('local/foo.txt', $this->field->subDir('local')->runValidation($this->file));

        Storage::delete('local/foo.txt');
    }

    public function testInvalidFileInput()
    {
        $this->setExpectedException(ValidationError::class);
        $this->field->runValidation('');
    }

    public function testFileOutput()
    {
        $this->assertEquals(URL::to('file.txt'), $this->field->toRepresentation('file.txt'));
        $this->assertEquals(URL::to('folder/file.txt'),
            $this->field->urlPrefix('folder')->toRepresentation('file.txt'));
    }
}
