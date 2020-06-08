<?php


namespace App\Model;

use App\Traits\Filterable;
use  \Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;


class Model extends EloquentModel
{
    use SoftDeletes, Filterable;
}
