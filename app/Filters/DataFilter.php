<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DataFilter extends QueryFilter
{
    protected $filed = 'name';

    /**
     * @param int $status
     * @return mixed
     */
    public function status($status = 3)
    {
        return $this->builder->where('status', $status);
    }


    /**
     * @param $date
     */
    public function created($date)
    {
        $this->dateBetween($date, 'created_at');
    }

    /**
     * @param $date
     */
    public function dates($date)
    {
        $dates = [$date[1], $date[2]];
        $this->dateBetween($dates, $date[0]);
    }

    /**
     * @param $date
     * @param string|null $filed
     * @return mixed
     */
    protected function dateBetween($date, string $filed = null)
    {
        if (is_array($date)) {
            $start = $date[1] ?? Carbon::now()->format('Y-m-d');
            return $this->builder->whereBetween($filed, [$start, $date[0]]);
        } else {
            $date = is_null($date) ? Carbon::now()->format('Y-m-d') : $date;
            return $this->builder->whereDate($filed, $date);
        }
    }

    /**
     * @param string $order
     * @return mixed
     */
    public function order($order = 'desc')
    {
        $filed = is_array($order) ? $order[1] : 'name';
        $order = is_array($order) ? $order[0] : 'desc';
        return $this->builder->orderBy($filed, $order);
    }

    public function orderBY()
    {
        return $this->builder->orderBy($this->request->orderBy, 'desc');
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function take($limit = 8)
    {
        return $this->builder->limit($limit);
    }

    /**
     * @param string $filed
     * @return mixed
     */
    public function latest($filed = 'updated_at')
    {
        return $this->builder->orderBy($filed, 'desc');
    }

    /**
     * @param string $filed
     */
    public function field($filed = 'title')
    {
        $this->filed = $filed;
    }


    /**
     * @param string $title
     * @return mixed
     */
    public function q($title = '')
    {
        if ($this->builder->getModel()->isFillable($this->filed)) {

            return $this->builder->where($this->filed, 'like', '%' . $title . '%');

        } elseif (strpos($this->filed, '.') !== false) {

            $fileds = explode('.', $this->filed);

            if (array_key_exists($fileds[0], $this->builder->getEagerLoads())) {

                return $this->builder->whereHas($fileds[0], function ($query) use ($title, $fileds) {
                    $query->where($fileds[1], 'like', '%' . $title . '%');
                });
            }
        }
    }


    /**
     * @param $title
     * @return mixed
     */
    public function name($title)
    {
        return $this->builder->where('title', 'like', '%' . $title . '%');
    }

    /**
     * @param $title
     * @return mixed
     */
    public function withRelation($title)
    {
        return $this->builder->with([$title => function ($query) use ($title) {
            $query->select($title . '.id', 'title');
        }]);
    }

    public function upcoming()
    {
        return $this->builder->where('updated_at', '>=', Carbon::now());
    }

    /**
     * @return mixed
     */
    public function today()
    {
        return $this->builder->whereDate('updated_at', Carbon::today()->format('Y-m-d'));
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public function except(array $ids)
    {
        return $this->builder->whereNotIn('id', $ids);
    }

}
