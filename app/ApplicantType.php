<?php

namespace App;

enum ApplicantType: string
{
    //
    case Student = 'student';
    case Staff = 'staff';
    case Faculty = 'faculty';

    public static function values(): array
    {
        return array_map(fn(self $type) => $type->value, self::cases());
    }
}
