<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coach_level_id',
        'experience',
        'introduction',
        'phone',
        'timeline',
        'character',
        'email',
        'avatar',
        'deleted',
    ];

    /**
     * Get the user that owns the Coach
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the coachLevel that owns the Coach
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coachLevel()
    {
        return $this->belongsTo(CoachLevel::class, 'coach_level_id');
    }
}
