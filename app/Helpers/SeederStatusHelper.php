<?php

namespace App\Helpers;

use App\Models\Status;
use Illuminate\Support\Arr;

class SeederStatusHelper
{

    public static function generateRandomStatus()
    {
        $code = Arr::random([
            'under_review',
            'approved',
            'rejected',
        ]);

        return Status::where('code', $code)->firstOrFail();
    }

}
