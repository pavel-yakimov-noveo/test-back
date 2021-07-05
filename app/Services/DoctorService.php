<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Doctor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DoctorService
{
    /**
     * @return LengthAwarePaginator
     */
    public function getDoctors(): LengthAwarePaginator
    {
        return Doctor::paginate();
    }
}
