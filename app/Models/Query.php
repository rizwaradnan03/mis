<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $table = 'queries';
    protected $fillable = [
        'report_name','query','flag'
    ];
    use HasFactory;
}
