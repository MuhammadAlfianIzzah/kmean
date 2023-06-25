<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engine extends Model
{
    use HasUuids, HasFactory;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ["nama", "jumlah_cluster", "max_literasi", "job_id", "elbow", "legent", "user_id"];

    public function historyEngine()
    {
        return $this->hasMany(HistoryEngine::class, "engine_id");
    }
}
