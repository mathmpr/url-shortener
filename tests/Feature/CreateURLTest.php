<?php

namespace Tests\Feature;

use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreateURLTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_random_url()
    {
        $response = $this->postJson('/api/url', ['origin' => 'https://moodle.org/mod/forum/discuss.php?d=310661']);

        $response->assertJson(function (AssertableJson $json) {
            return $json->has('url')
                ->missing('errors');
        });
    }

    public function test_create_defined_url()
    {
        $response = $this->postJson('/api/url', ['origin' => 'https://docs.moodle.org/dev/Using_the_File_API_in_Moodle_forms#filemanager', 'target' => 'moodle-filemanager']);

        $response->assertJson(function (AssertableJson $json) {
            return $json->has('url')
                ->missing('errors');
        });
    }

    public function test_create_defined_url_with_same_target()
    {
        // the test test_create_defined_url already creates `moodle-filemanager` target, then this test return errors
        $response = $this->postJson('/api/url', ['origin' => 'https://docs.moodle.org/dev/Form_API', 'target' => 'moodle-filemanager']);

        $response->assertJson(function (AssertableJson $json) {
            return $json->has('errors');
        });
    }

    public function test_change_created_at_seven_days_old_and_create_same_defined_url()
    {
        // if this process fails ...
        $url = Url::whereTarget('moodle-filemanager')->first();
        $url->created_at = Carbon::now()->subDays(7)->subMinutes(30)->toDateTimeString();
        $url->save();

        // ... this process also fails
        $this->test_create_defined_url();
    }


}
