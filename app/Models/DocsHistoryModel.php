<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocsHistoryModel extends Model
{
    protected $table = 'document_history';
    protected $primaryKey = 'Id';
    protected $fillable = ['ProjectId', 'Title', 'Document'];
    
    // Jika menggunakan custom timestamp column
    const CREATED_AT = 'DateAdded';
    const UPDATED_AT = 'DateAdded'; // atau null jika tidak ada updated column
    
    // Jika primary key bukan auto-increment integer
    protected $keyType = 'int';
    public $incrementing = true;
    
    // Custom date format jika diperlukan
    protected $dateFormat = 'Y-m-d H:i:s';
}
