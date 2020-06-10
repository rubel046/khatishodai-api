<?php

namespace App\Filters;


class CompanyFilter extends DataFilter
{
    /**
     * @param $id
     * @return mixed
     */
    public function company_id($id)
    {
        return $this->builder->where('company_id', $id);
    }

}
