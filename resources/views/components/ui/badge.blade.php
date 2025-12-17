@props(['label', 'type' => 'status'])

@php
$label = is_string($label) ? trim(ucwords(strtolower($label))) : $label;

$classes = match ($label) {
    // Status badges
    "Pending" => "bg-yellow-100/80 text-yellow-800 hover:bg-yellow-200/60",
    "Under Review" => "bg-amber-100/80 text-amber-800 hover:bg-amber-200/60",
    "Approved" => "bg-green-100/80 text-green-800 hover:bg-green-200/60",
    "Rejected" => "bg-red-100/80 text-red-800 hover:bg-red-200/60",
    "Active" => "bg-emerald-100/80 text-emerald-800 hover:bg-emerald-200/60",
    "Inactive" => "bg-gray-100/80 text-gray-800 hover:bg-gray-200/60",
    "Expired" => "bg-slate-100/80 text-slate-800 hover:bg-slate-200/60",
    "Revoked" => "bg-orange-100/80 text-orange-800 hover:bg-orange-200/60",
    "Blacklisted" => "bg-red-200/80 text-red-900 hover:bg-red-300/60",
    "Flagged" => "bg-rose-100/80 text-rose-800 hover:bg-rose-200/60",
    "Lost" => "bg-blue-100/80 text-blue-800 hover:bg-blue-200/60",
    "Stolen" => "bg-gray-200/80 text-gray-900 hover:bg-gray-300/60",
    "Maintenance" => "bg-sky-100/80 text-sky-800 hover:bg-sky-200/60",

    // Role badges
    "Super Admin" => "bg-purple-100/80 text-purple-800 hover:bg-purple-200/60",
    "Admin Editor" => "bg-blue-100/80 text-blue-800 hover:bg-blue-200/60",
    "Admin Viewer" => "bg-teal-100/80 text-teal-800 hover:bg-teal-200/60",
    "Admin" => "bg-sky-100/80 text-sky-800 hover:bg-sky-200/60",
    "Encoder" => "bg-indigo-100/80 text-indigo-800 hover:bg-indigo-200/60",
    "Staff" => "bg-indigo-100/80 text-indigo-800 hover:bg-indigo-200/60",
    "Faculty" => "bg-green-100/80 text-green-800 hover:bg-green-200/60",
    "Student" => "bg-yellow-100/80 text-yellow-800 hover:bg-yellow-200/60",

    // Fallback
    default => "bg-red-100 text-red-800 hover:bg-red-200/60",
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors $classes"]) }}>
    {{ $label }}
</span>
