<?php

namespace App\Filters;


class RegionFilter extends DataFilter
{
    /**
     * @param $id
     * @return mixed
     */
    public function country_id($id)
    {
        return $this->builder->where('country_id', $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function division_id($id)
    {
        return $this->builder->where('division_id', $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function city_id($id)
    {
        return $this->builder->where('city_id', $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function zone_id($id)
    {
        return $this->builder->where('zone_id', $id);
    }

}
