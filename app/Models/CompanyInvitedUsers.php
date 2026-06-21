<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyInvitedUsers extends Model
{
    use SoftDeletes;

    public $table = 'tbl_company_invited_users';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'company_id',
        'user_id',
        'invited_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];    
}
