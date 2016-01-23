<?php namespace Kahire\Tests\Serializers\Fields;

use Illuminate\Support\Facades\Storage;
use Kahire\Serializers\Fields\Exceptions\ValidationError;
use Kahire\Serializers\Fields\FileField;
use Symfony\Component\HttpFoundation\File\File;

class FileFieldTest extends FieldTestCase {

    public $fieldClass = FileField::class;

    /**
     * @var File
     */
    public $file;


    public function setUp()
    {
        parent::setUp();

        Storage::put("foo.txt", "Hi!");
        $this->file = new File(storage_path("foo.txt"));
    }


    public function tearDown()
    {
        Storage::delete("foo.txt");

        parent::tearDown();
    }


    public function testFileInput()
    {
        $this->assertEquals($this->file, $this->field->runValidation($this->file));
    }


    public function testInvalidFileInput()
    {
        $this->setExpectedException(ValidationError::class);
        $this->field->runValidation("");
    }


    public function testFileOutput()
    {
        $this->assertEquals($this->file->getRealPath(), $this->field->toRepresentation($this->file));
    }

}
