@props([
    'name' => '',
    'email' => '',
    'initials' => '',
    'avatar_bg' => 'bg-blue-500',
    'role' => '',
    'role_color' => 'green',
    'status' => 'active',
    'last_login' => '',
    'created_date' => '',
    'department' => ''
])

<tr class="hover:bg-gray-50 transition-colors duration-200">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full {{ $avatar_bg }} flex items-center justify-center text-white font-semibold text-sm">
                {{ $initials }}
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">{{ $name }}</div>
                <div class="text-sm text-gray-500">{{ $email }}</div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $role_color }}-100 text-{{ $role_color }}-800">
                {{ $role }}
            </span>
            <span class="ml-2 text-xs text-gray-500">{{ $department }}</span>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            @if($status === 'active')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                    Active
                </span>
            @elseif($status === 'pending')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    <span class="w-2 h-2 bg-amber-400 rounded-full mr-1.5"></span>
                    Pending
                </span>
            @elseif($status === 'inactive')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <span class="w-2 h-2 bg-red-400 rounded-full mr-1.5"></span>
                    Inactive
                </span>
            @endif
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
        {{ $last_login }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $created_date }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <div class="flex items-center justify-end space-x-2">
            <button class="text-blue-600 hover:text-blue-900 transition-colors duration-200" title="Edit User">
                <i class="fas fa-edit text-sm"></i>
            </button>
            <button class="text-green-600 hover:text-green-900 transition-colors duration-200" title="View User">
                <i class="fas fa-eye text-sm"></i>
            </button>
            <button class="text-red-600 hover:text-red-900 transition-colors duration-200" title="Delete User">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    </td>
</tr>
