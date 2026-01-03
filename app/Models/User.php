<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\ApplicationDisplayHelper;
use App\Models\Vehicle\Vehicle;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $keyType = "string";
    public $incrementing = false;
    protected $fillable = [
        "email",
        "must_change_password",
        "password",
        "is_active",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ["password", "remember_token"];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function details()
    {
        return $this->hasOne(UserDetails::class, "user_id", "id");
    }

    public function applications()
    {
        return $this->hasMany(Application::class, "user_id", "id");
    }

    public function documents()
    {
        return $this->hasManyThrough(
            Documents::class,
            Application::class,
            "user_id",
            "application_id",
            "id",
            "id",
        );
    }

    public function approvedDetailes()
    {
        return $this->hasMany(UserDetails::class, "approved_by", "uuid");
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, "owner_id");
    }

    public function getPhoneNumberAttribute()
    {
        return ApplicationDisplayHelper::formatPhoneNumber(
            $this->details?->phone_number,
        );
    }

    public function getNameInitialAttribute()
    {
        return ApplicationDisplayHelper::generateNameThumbnail(
            $this->details?->first_name,
            $this->details?->last_name,
        );
    }

    // applicant details card details
    public function getCardDetails($context = "default")
    {
        $data = [];

        if($context === 'default') {
            $data = [
                "first_name" => "First Name",
                "middle_name" => "Middle Name",
                "last_name" => "Last Name",
                "suffix" => "Suffix",
                "email" => "Email",
                "phone_number" => "Phone Number",
                "license_number" => "License Number",
            ];
        }elseif($context === 'address') {
            $data = [
                'region_name' => 'Region',
                'province' => 'Province',
                'municipality' => 'City/Municipality',
                'barangay' => 'Barangay',
                'zip_code' => 'Zip Code'
            ];
        }

        return $data;
    }

    public function scopeApplicant($query)
    {
        return $query
            ->whereHas("roles", fn($q) => $q->where("name", "applicant"))
            ->with([
                "details:user_id,clsu_id,phone_number,first_name,middle_name,last_name,suffix",
                "applications:user_id,status_id,applicant_type",
                "applications.status:id,status_name",
            ]);
    }

    public function scopeAdmin($query)
    {
        // Fetch all admin roles except security and applicant
        return $query
            ->whereHas("roles", fn($q) => $q->whereNotIn("name", ["security", "applicant"]))
            ->with([
                "details:user_id,clsu_id,phone_number,first_name,middle_name,last_name,suffix",
                "roles:id,name",
            ]);
    }

    public function scopeAdminCount($query)
    {
        return (object) [
            "total" => (clone $query)->count(),
            "super_admin" => (clone $query)
                ->whereHas("roles", fn($q) => $q->where("name", "super_admin"))
                ->count(),
            "admin" => (clone $query)
                ->whereHas("roles", fn($q) => $q->whereNotIn("name", ["super_admin", "security", "applicant"]))
                ->count(),
        ];
    }

    public function scopeApplicantCount($query)
    {
        $baseQuery = $query->whereHas("applications.status");

        return (object) [
            "total" => $baseQuery->count(),
            "active" => $baseQuery
                ->whereHas(
                    "applications.status",
                    fn($q) => $q->where("code", "approved"),
                )
                ->count(),
            "rejected" => $baseQuery
                ->whereHas(
                    "applications.status",
                    fn($q) => $q->where("code", "rejected"),
                )
                ->count(),
        ];
    }

    public function scopeWithStatusCode($query, string $status)
    {
        return $query->whereHas(
            "applications.status",
            fn($q) => $q->where("code", $status),
        );
    }

    public function scopeWithApplicationCounts($query)
    {
        return $query->withCount([
            "applications",
            "applications as pending_applications_count" => function ($query) {
                $query->whereHas("status", function ($q) {
                    $q->where("code", "under_review");
                });
            },
            "applications as active_applications_count" => function ($query) {
                $query->whereHas("status", function ($q) {
                    $q->where("code", "active");
                });
            },
            "applications as rejected_applications_count" => function ($query) {
                $query->whereHas("status", function ($q) {
                    $q->where("code", "rejected");
                });
            },
        ]);
    }

    public function scopeSearchTerm($query, string $term)
    {
        $term = trim($term);
        $tokens = preg_split("/\s+/", $term, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function ($q2) use ($term, $tokens) {
            // Primary search: clsu_id or email
            $q2->whereHas(
                "details",
                fn($d) => $d
                    ->where("clsu_id", "like", "{$term}%")
                    ->orWhere(function ($q3) use ($tokens) {
                        foreach ($tokens as $token) {
                            $q3->where(function ($q4) use ($token) {
                                $q4->where(
                                    "first_name",
                                    "like",
                                    "{$token}%",
                                )->orWhere("last_name", "like", "{$token}%");
                            });
                        }
                    }),
            )->orWhere("email", "like", "%{$term}%");
        });
    }

    public function scopeApplicantType($query, $types)
    {
        if (empty($types)) {
            return $query;
        }

        if (!is_array($types)) {
            $types = array_map("trim", explode(",", $types));
        }

        return $query->whereHas(
            "applications",
            fn($q) => $q->whereIn("applicant_type", $types),
        );
    }

    public function scopeSortApplicant($query, $option)
    {
        $sortOptions = [
            "newest" => ["created_at", "desc"],
            "oldest" => ["created_at", "asc"],
            "a-z" => ["first_name", "asc"],
            "z-a" => ["first_name", "desc"],
        ];

        [$column, $direction] = $sortOptions[$option] ?? ["created_at", "asc"];

        if ($column === "first_name") {
            return $query->orderBy(
                UserDetails::select("first_name")
                    ->whereColumn("user_details.user_id", "users.id")
                    ->limit(1),
                $direction,
            );
        }

        return $query->orderBy($column, $direction);
    }
}
