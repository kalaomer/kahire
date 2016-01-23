<?php namespace tests\Kahire\Serializers\Fields;

use Illuminate\Support\Facades\Storage;
use Kahire\Serializers\Fields\FileStorageField;
use Kahire\Tests\Serializers\Fields\FieldTestCase;
use Symfony\Component\HttpFoundation\File\File;

class FileStorageFieldTest extends FieldTestCase {

    public $fieldClass = FileStorageField::class;

    /**
     * @var File
     */
    public $file;


    public function setUp()
    {
        parent::setUp();

        Storage::put("tmp/test_file.txt", "Hi!");
        $this->file = new File(storage_path("tmp/test_file.txt"));
    }


    public function tearDown()
    {
        Storage::delete("tmp/test_file.txt");

        parent::tearDown();
    }


    public function testUploadFile()
    {
        $this->field->runValidation($this->file);

        $this->assertFileExists(storage_path("test_file.txt"));

        Storage::delete("test_file.txt");
    }
}
