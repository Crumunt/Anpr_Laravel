<?php

namespace App\Http\Resources;

use App\Helpers\ApplicationDisplayHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    protected $context;

    public function __construct($resource, $context = "list")
    {
        parent::__construct($resource);
        $this->context = $context;
    }

    public static function forList($resource)
    {
        return new static($resource, "list");
    }

    public static function forDetail($resource)
    {
        return new static($resource, "detail");
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "email" => $this->email,
            "created_date" => $this->created_at->format("F d, Y"),
            "is_active" => $this->is_active ?? true,

            ...$this->relationLoaded("details")
                ? [
                    "name" => $this->details?->full_name ?? 'No Name',
                    "clsu_id" => $this->details?->clsu_id ?? "-",
                    "phone_number" => ApplicationDisplayHelper::formatPhoneNumber($this->details?->phone_number) ?? "-",
                ]
                : [],

            ...$this->relationLoaded("roles")
                ? $this->getRoleData()
                : [],
        ];
    }

    private function getRoleData()
    {
        $role = $this->roles->first();
        $roleName = $role?->name ?? 'admin';

        return [
            "role" => [
                "badge_label" => $this->formatRoleName($roleName),
                "badge_class" => $this->getRoleBadgeClass($roleName),
            ],
            "role_name" => $roleName,
        ];
    }

    private function formatRoleName(string $role): string
    {
        return match($role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            default => ucfirst(str_replace('_', ' ', $role)),
        };
    }

    private function getRoleBadgeClass(string $role): string
    {
        return match($role) {
            'super_admin' => 'bg-purple-100 text-purple-800',
            'admin' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
