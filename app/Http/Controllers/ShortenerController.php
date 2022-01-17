<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ShortenerController extends Controller
{
    public $validators = [
        'create' => [
            'origin' => 'string|url|unique:urls,origin,NULL,id,deleted_at,NULL|required',
            'target' => 'string|max:255|unique:urls,target,NULL,id,deleted_at,NULL',
        ]
    ];

    private function expireAllUrl(){
        // every create request update urls and expires old urls
        DB::update("UPDATE urls SET deleted_at = \"" . Carbon::now()->toDateTimeString() . "\" WHERE cast((strftime('%s', created_at) + (7 * 24 * 60 * 60)) as int) <= cast(strftime('%s', 'now') as int)");
    }

    public function create(Request $request)
    {
        $this->expireAllUrl();

        $valid = Validator::make($request->all(), $this->validators['create']);
        if (!$valid->fails()) {
            if ($request->target) {
                $target = $request->target;
            } else {
                $target = Url::random();
                while (Url::whereTarget($target)->count() > 0) {
                    $target = Url::random();
                }
            }

            Url::create([
                'origin' => $request->origin,
                'target' => $target
            ]);

            return response()->json([
                'url' => app()->make('url')->to('/') . '/' . $target
            ]);
        }

        $errors = $valid->errors();
        if (array_key_exists('origin', $errors->toArray())) {
            $url = Url::whereOrigin($request->origin)->first();
            if ($url) {
                return response()->json([
                    'url' => app()->make('url')->to('/') . '/' . $url->target
                ]);
            }
        }

        return response()->json(['errors' => $valid->errors()], 400);

    }

    function get(Request $request, $url_key = null){
        $this->expireAllUrl();
        $url = Url::whereTarget($url_key)->first();
        if($url){
            return redirect($url->origin);
        }
        return abort(404);
    }

}
