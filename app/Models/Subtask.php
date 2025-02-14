<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\SubtaskObserver;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'title', 'description', 'status'];

    // Relationship to the Task model
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Boot method for model observers
    protected static function boot()
    {
        parent::boot();
        static::observe(SubtaskObserver::class); 
    }
}