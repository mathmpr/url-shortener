<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Url extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'origin',
        'target'
    ];

    static function random($length = 8)
    {
        $az = 'abcdefghijklmnopqrstuvwxyz';
        if($length > (strlen($az) * 2 + 10)) $length = 8;
        $letters = str_split($az, 1);
        $numbers = str_split('0123456789', 1);
        $upper = str_split(strtoupper($az), 1);
        $result = array_merge($letters, $numbers, $upper);
        shuffle($result);
        return substr(join('', $result), 0, $length);
    }
}
