<!-- It is never too late to be what you might have been. - George Eliot -->

@if ($key === 'status' && is_array($row['status'] ?? null))

    <x-badge type="status" :label="$row['status']['label'] ?? $value" />

@elseif($key === 'role' && $type === 'admin')
    <x-badge type="role" :label="$value" />
@else
    {{ $value }}
@endif