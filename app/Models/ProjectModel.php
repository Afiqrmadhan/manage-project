<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjectModel extends Model
{
    protected $table = 'project';
    protected $primaryKey = 'Id';
    protected $fillable = ['ProjectManager', 'ProjectManagerId', 'Title', 'ClientCompany', 'ClientName', 'ProjectSchedule', 'UATHistoryId'];
    public $timestamps = false; // Jika tabel tidak menggunakan created_at dan updated_at
    
    // Jika primary key bukan auto-increment integer
    protected $keyType = 'int';
    public $incrementing = true;

    // Fungsi untuk memanggil procedure AddProject
    public function addProjectUsingProcedure($ProjectManager, $Title, $ClientCompany, $ClientName, $ProjectSchedule)
    {
        DB::statement('CALL AddProject(?, ?, ?, ?, ?)', [$ProjectManager, $Title, $ClientCompany, $ClientName, $ProjectSchedule]);
    }

    public function searchProjects($keyword)
    {
        return $this->where('Title', 'like', '%' . $keyword . '%')->get();
    }
}