<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\ApplicationDisplayHelper;
use App\Models\Vehicle\Vehicle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffx',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function details()
    {
        return $this->hasOne(UserDetails::class, 'user_id', 'id');
    }

    public function approvedDetailes()
    {
        return $this->hasMany(UserDetails::class, 'approved_by', 'uuid');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'owner_id');
    }

    public function getFullNameAttribute(): string
    {
        return ApplicationDisplayHelper::getFullNameAttribute(
            $this->first_name,
            $this->middle_name,
            $this->last_name
        );
    }

    public function getNameInitialAttribute()
    {
        return ApplicationDisplayHelper::generateNameThumbnail($this->first_name, $this->last_name);
    }

    public function scopeApplicant($query)
    {
        return $query->whereHas('roles', fn($q) => $q->where('name', 'applicant'));
    }

    public function scopeWithStatusCode($query, string $status)
    {
        return $query->whereHas('details.status', fn($q) => $q->where('code', $status));
    }

    public function scopeSearchTerm($query, string $term)
    {
        $term = trim($term);
        $tokens = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function ($q2) use ($term, $tokens) {
            // Primary search: clsu_id or email
            $q2->whereHas(
                'details',
                fn($d) =>
                $d->where('clsu_id', 'like', "{$term}%")
            )
                ->orWhere('email', 'like', "%{$term}%");

            // Name tokens: any of the terms matching first or last name
            if (!empty($tokens)) {
                $q2->orWhere(function ($q3) use ($tokens) {
                    foreach ($tokens as $token) {
                        $q3->where(function ($q4) use ($token) {
                            $q4->where('first_name', 'like', "{$token}%")
                                ->orWhere('last_name', 'like', "{$token}%");
                        });
                    }
                });
            }
        });
    }

    public function scopeApplicantType($query, $types)
    {
        if (empty($types)) {
            return $query;
        }

        if (!is_array($types)) {
            $types = array_map('trim', explode(',', $types));
        }

        return $query->whereHas('details', fn($q) => $q->whereIn('applicant_type', $types));
    }

    public function scopeSortApplicant($query, $option) {
        $sortOptions = [
            'newest' => ['created_at', 'desc'],
            'oldest' => ['created_at', 'asc'],
            'a-z' => ['first_name', 'asc'],
            'z-a' => ['first_name', 'desc'],
        ];

        if(isset($sortOptions[$option])) {
            [$column, $direction] = $sortOptions[$option];
            return $query->orderBy($column, $direction);
        }
    }
}
