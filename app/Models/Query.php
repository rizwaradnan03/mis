<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $table = 'queries';
    protected $fillable = [
        'report_name','report_name_display','query','flag'
    ];
    use HasFactory;
}
