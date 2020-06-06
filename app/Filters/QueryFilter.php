<?php
/**
 * Created by PhpStorm.
 * User: alamincse
 * Date: 8/26/2017
 * Time: 1:57 PM
 */

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class QueryFilter
{
    protected $request;
    protected $builder;

    /**
     * QueryFilter constructor.
     */
    public function __construct()
    {
        $this->request = request();
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach ($this->filters() as $name => $value) {
            if ($value && method_exists($this, $name)) {
                call_user_func_array([$this, $name], array_filter([$value]));
            }
        }
        return $this->builder;
    }

    /**
     * @return array
     */
    public function filters()
    {
        return $this->request->all();
    }
}
