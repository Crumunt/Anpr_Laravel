<?php

namespace App\Helpers;

use App\Models\Status;
use Illuminate\Support\Arr;

class SeederStatusHelper
{

    public static function generateRandomStatus()
    {
        $code = Arr::random([
            'pending',
            'approved',
            'rejected',
        ]);

        return Status::where('code', $code)->firstOrFail();
    }

}
