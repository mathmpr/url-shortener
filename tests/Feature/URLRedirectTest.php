<?php

namespace Tests\Feature;

use App\Models\Url;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Tests\TestCase;

class URLRedirectTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_if_url_after_redirect_is_same_stored_in_origin_database()
    {
        $url = Url::whereTarget('moodle-filemanager')->first();
        if ($url) {
            $client = new Client();

            $client->request('GET', app()->make('url')->to('/') . ':8000/' . $url->target, [
                'allow_redirects' => true,
                'on_stats' => function (TransferStats $stats) use (&$guzzleUrl) {
                    $guzzleUrl = $stats->getEffectiveUri();
                }
            ]);

            $this->assertEquals($url->origin, $guzzleUrl->__toString());
        }
    }
}
