<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $fillable = ['username', 'password', 'level'];
    public $timestamps = false; // Jika tabel tidak menggunakan created_at dan updated_at

    public function getProjectManagers()
    {
        return $this->where('level', 2)->get();
    }
}
