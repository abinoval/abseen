<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all of the absent for the Rombel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absent()
    {
        return $this->hasMany(Absent::class);
    }
}
