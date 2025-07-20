<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureUATModel extends Model
{
    protected $table = 'feature_uat';
    protected $primaryKey = 'Id';
    protected $fillable = ['ProjectId', 'Feature', 'UATDate', 'ValidationStatus', 'ClientFeedbackStatus', 'RevisionNotes'];
    public $timestamps = false; // Jika tabel tidak menggunakan created_at dan updated_at
    
    // Jika primary key bukan auto-increment integer
    protected $keyType = 'int';
    public $incrementing = true;
}