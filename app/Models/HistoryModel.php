<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryModel extends Model
{
    protected $table = 'uathistory';
    protected $primaryKey = 'Id';
    protected $fillable = ['ProjectManager', 'ProjectManagerId', 'Title', 'ProjectId', 'Status'];
    public $timestamps = false; // Jika tabel tidak menggunakan created_at dan updated_at
    
    // Jika primary key bukan auto-increment integer
    protected $keyType = 'int';
    public $incrementing = true;
}
