<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory,HasUuids,SoftDeletes;

    protected $guarded = ['id'];

    public function products():BelongsToMany{

        return $this->belongsToMany(Product::class)->withPivot(['quantity']);

    }
}