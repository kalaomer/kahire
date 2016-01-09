<?php namespace tests\Kahire\Serializers;

use Illuminate\Filesystem\ClassFinder;
use Illuminate\Filesystem\Filesystem;
use Kahire\Serializers\Fields\IntegerField;
use Kahire\Serializers\Fields\StringField;
use Kahire\Serializers\ModelSerializer;
use tests\db\models\FooModel;

class ModelSerializerTest extends \TestCase {

    /**
     * @var ModelSerializer
     */
    public $serializer;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','sqlite');
        $this->app['config']->set('database.connections.sqlite.database', ':memory:');

        $this->migrate();

        $this->serializer = new class extends ModelSerializer {
            public $model = FooModel::class;

            public function getFields()
            {
                return [
                    "string" => StringField::generate(),
                    "integer" => IntegerField::generate()
                ];
            }
        };
    }

    public function migrate()
    {
        $fileSystem = new Filesystem;
        $classFinder = new ClassFinder;

        foreach($fileSystem->files(__DIR__ . "/../../db/migrations") as $file)
        {
            $fileSystem->requireOnce($file);
            $migrationClass = $classFinder->findClass($file);

            (new $migrationClass)->up();
        }
    }

    public function testCreate()
    {
        $validData = [
            "string" => "string",
            "integer" => 1
        ];

        $this->serializer->data($validData)->isValid();
        $this->serializer->save();

        $this->assertEquals($validData, FooModel::all("string", "integer")->last()->toArray());
    }

    public function testUpdate()
    {
        $validData = [
            "string" => "string",
            "integer" => 1
        ];

        $instance = new FooModel();
        $instance->string = "string";
        $instance->integer = 1;
        $instance->save();

        $validUpdate = [
            "string" => "new string"
        ];

        $validData["string"]= $validUpdate["string"];

        $this->serializer->instance($instance)->data($validUpdate)->partial(true);
        $this->assertEquals(true, $this->serializer->isValid());

        $this->serializer->save();

        $this->assertEquals($validData, FooModel::select("string", "integer")->where("id", $instance->id)->first()->toArray());
    }
}
