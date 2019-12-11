<?php

namespace PbbgIo\TitanFramework\Commands;

use App\User;
use Carbon\Carbon;
use Carbon\Laravel\ServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use PbbgIo\TitanFramework\Models\Settings;

class RefreshExtensionsCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'titan:extension:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush the caches of Titan extensions';

    /**
     * Create a new command instance.
     *
     * @return void
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $this->getExtensions();
        \Storage::disk('local')->put('extensions.json', json_encode($this->schema));
        $this->info("Extensions have been reloaded");

        $setting = Settings::firstOrNew([
            'key'   =>  'remote_version'
        ]);
        $setting->value = $this->getRemoteVersion();
        $setting->save();
    }

    /**
     * Get the latest version of the software that's available
     *
     * @return string
     */
    private function getRemoteVersion(): string {

        $http = new Client();
        $res = $http->get('https://titan.pbbg.io/api/version')->getBody()->getContents();
        $res = json_decode($res);
        return $res->version;
    }

    /**
     * Get extensions from a remote source
     *
     * @todo Grab them remotely then cache them
     * @throws \Exception
     */
    private function getExtensions(): void
    {

        $extensions = [
            [
                'name' => 'Hello World',
                'description' => 'Hello World provides a very basic example of what an extension can do',
                'version' => '1.0.0',
                'authors' => [
                    [
                        'name' => 'Ian',
                        'email' => 'ian@pbbg.io'
                    ]
                ],
                'path'  =>  'ian/hello-world',
                'slug' => 'hello-world',
                'rating' => '4.0',
                'ratings' => 20,
                'installs' => 3237
            ],
            [
                'name' => 'Test World',
                'description' => 'Test World provides a very basic example of what an extension can do',
                'version' => '1.0.0',
                'authors' => [
                    [
                        'name' => 'Ian',
                        'email' => 'ian@pbbg.io'
                    ]
                ],
                'path'  =>  'ian/test-world',
                'slug' => 'test-world',
                'rating' => '3.0',
                'ratings' => 174,
                'installs' => 1474
            ],
        ];

        $this->schema['date'] = new Carbon();
        $this->schema['extensions'] = $extensions;
    }
}
