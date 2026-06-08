<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    protected $fillable = ['table_name', 'record_id', 'action', 'payload', 'synced', 'error_message'];
}
