<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShortUrl extends Model
{
    use SoftDeletes;

    public $table = 'tbl_short_urls';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'original_url',
        'short_url',
        'company_id',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];    
}
