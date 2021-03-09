<?php

namespace Modules\Modulemanager\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MModule extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','settings'];

    protected static function newFactory()
    {
        return \Modules\Modulemanager\Database\factories\MModuleFactory::new();
    }
}
