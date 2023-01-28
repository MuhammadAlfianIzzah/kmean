<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataProses extends Model
{
    protected $fillable = ["user_id", "progress_id"];
    use HasFactory;
    public function klusters()
    {
        return $this->hasMany(Kluster::class, "data_proses_id");
    }
}
