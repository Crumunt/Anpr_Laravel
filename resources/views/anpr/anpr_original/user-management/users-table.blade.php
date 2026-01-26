@props([
    'users' => []
])

@php
    // Sample data if no users provided
    if (empty($users)) {
        $users = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john.doe@clsu.edu.ph',
                'role' => 'admin',
                'status' => 'active',
                'department' => 'IT Department',
                'phone' => '+63 912 345 6789',
                'lastLogin' => '2024-01-15 14:30:00',
                'createdAt' => '2023-06-15'
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane.smith@clsu.edu.ph',
                'role' => 'operator',
                'status' => 'active',
                'department' => 'Security',
                'phone' => '+63 923 456 7890',
                'lastLogin' => '2024-01-14 09:15:00',
                'createdAt' => '2023-08-20'
            ],
            [
                'id' => 3,
                'name' => 'Mike Johnson',
                'email' => 'mike.johnson@clsu.edu.ph',
                'role' => 'viewer',
                'status' => 'pending',
                'department' => 'Administration',
                'phone' => '+63 934 567 8901',
                'lastLogin' => null,
                'createdAt' => '2024-01-10'
            ],
            [
                'id' => 4,
                'name' => 'Sarah Wilson',
                'email' => 'sarah.wilson@clsu.edu.ph',
                'role' => 'operator',
                'status' => 'inactive',
                'department' => 'Security',
                'phone' => '+63 945 678 9012',
                'lastLogin' => '2024-01-05 16:45:00',
                'createdAt' => '2023-09-12'
            ],
            [
                'id' => 5,
                'name' => 'David Brown',
                'email' => 'david.brown@clsu.edu.ph',
                'role' => 'admin',
                'status' => 'active',
                'department' => 'IT Department',
                'phone' => '+63 956 789 0123',
                'lastLogin' => '2024-01-15 11:20:00',
                'createdAt' => '2023-07-08'
            ]
        ];
    }
@endphp

<div class="overflow-x-auto">
    <table id="users-table" class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr class="bg-gray-50">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <div class="flex items-center">
                        <input type="checkbox" id="select-all" class="mr-3 rounded border-gray-300 text-green-600 focus:ring-green-500 h-4 w-4">
                        <button class="flex items-center focus:outline-none" onclick="sortTable(0)">
                            User
                            <i class="fas fa-sort ml-1 text-gray-400" aria-hidden="true"></i>
                        </button>
                    </div>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button class="flex items-center focus:outline-none" onclick="sortTable(1)">
                        Role
                        <i class="fas fa-sort ml-1 text-gray-400" aria-hidden="true"></i>
                    </button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button class="flex items-center focus:outline-none" onclick="sortTable(2)">
                        Department
                        <i class="fas fa-sort ml-1 text-gray-400" aria-hidden="true"></i>
                    </button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button class="flex items-center focus:outline-none" onclick="sortTable(3)">
                        Status
                        <i class="fas fa-sort ml-1 text-gray-400" aria-hidden="true"></i>
                    </button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button class="flex items-center focus:outline-none" onclick="sortTable(4)">
                        Last Login
                        <i class="fas fa-sort ml-1 text-gray-400" aria-hidden="true"></i>
                    </button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button class="flex items-center focus:outline-none" onclick="sortTable(5)">
                        Created
                        <i class="fas fa-sort ml-1 text-gray-400" aria-hidden="true"></i>
                    </button>
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($users as $user)
                <tr class="hover:bg-{{ $user['status'] === 'active' ? 'green' : ($user['status'] === 'pending' ? 'amber' : 'red') }}-50 transition-colors" 
                    data-user-id="{{ $user['id'] }}"
                    data-name="{{ $user['name'] }}" 
                    data-email="{{ $user['email'] }}" 
                    data-role="{{ $user['role'] }}" 
                    data-status="{{ $user['status'] }}"
                    data-department="{{ $user['department'] ?? 'IT Department' }}"
                    data-phone="{{ $user['phone'] ?? '+63 912 345 6789' }}"
                    data-last-login="{{ $user['lastLogin'] ?? '' }}"
                    data-created-at="{{ $user['createdAt'] ?? '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <input type="checkbox" class="mr-3 rounded border-gray-300 text-green-600 focus:ring-green-500 h-4 w-4" value="{{ $user['id'] }}">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-{{ $user['status'] === 'active' ? 'green' : ($user['status'] === 'pending' ? 'amber' : 'red') }}-100 flex items-center justify-center {{ $user['status'] === 'active' ? 'clsu-text' : ($user['status'] === 'pending' ? 'text-amber-600' : 'text-red-600') }}">
                                <i class="fas fa-user text-sm" aria-hidden="true"></i>
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $user['name'] }}</div>
                                    @if($user['role'] === 'admin')
                                        <span class="ml-2 px-1.5 py-0.5 bg-purple-100 text-purple-800 text-xs rounded">Admin</span>
                                    @elseif($user['role'] === 'operator')
                                        <span class="ml-2 px-1.5 py-0.5 bg-blue-100 text-blue-800 text-xs rounded">Operator</span>
                                    @else
                                        <span class="ml-2 px-1.5 py-0.5 bg-gray-100 text-gray-800 text-xs rounded">Viewer</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $user['email'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            @if($user['role'] === 'admin')
                                Administrator
                            @elseif($user['role'] === 'operator')
                                Operator
                            @else
                                Viewer
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">
                            @if($user['role'] === 'admin')
                                Full system access
                            @elseif($user['role'] === 'operator')
                                Limited operations
                            @else
                                Read-only access
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $user['department'] ?? 'IT Department' }}</div>
                        <div class="text-xs text-gray-500 flex items-center mt-0.5">
                            <i class="fas fa-building mr-1 text-gray-400" aria-hidden="true"></i>
                            Department
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $user['status'] === 'active' ? 'green' : ($user['status'] === 'pending' ? 'amber' : 'red') }}-100 text-{{ $user['status'] === 'active' ? 'green' : ($user['status'] === 'pending' ? 'amber' : 'red') }}-800">
                            <span class="w-1.5 h-1.5 bg-{{ $user['status'] === 'active' ? 'green' : ($user['status'] === 'pending' ? 'amber' : 'red') }}-600 rounded-full mr-1.5 mt-1"></span> 
                            {{ ucfirst($user['status']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user['lastLogin'])
                            <div class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($user['lastLogin'])->format('M j, Y') }}
                            </div>
                            <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                <i class="far fa-clock mr-1 text-gray-400" aria-hidden="true"></i>
                                {{ \Carbon\Carbon::parse($user['lastLogin'])->format('g:i A') }}
                            </div>
                        @else
                            <span class="text-sm text-gray-400">Never</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($user['createdAt'])->format('M j, Y') }}
                        </div>
                        <div class="text-xs text-gray-500 flex items-center mt-0.5">
                            <i class="fas fa-calendar-plus mr-1 text-gray-400" aria-hidden="true"></i>
                            {{ \Carbon\Carbon::parse($user['createdAt'])->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex space-x-1.5 justify-end">
                            <button class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-1.5 rounded-lg transition-colors tooltip" aria-label="View details" onclick="openViewUserModal({{ $user['id'] }})">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                                <span class="tooltip-text">View details</span>
                            </button>
                            <button class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 p-1.5 rounded-lg transition-colors tooltip" aria-label="Edit user" onclick="openEditUserModal({{ $user['id'] }})">
                                <i class="fas fa-edit" aria-hidden="true"></i>
                                <span class="tooltip-text">Edit user</span>
                            </button>
                            <button class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded-lg transition-colors tooltip" aria-label="Delete user" onclick="openDeleteUserModal({{ $user['id'] }})">
                                <i class="fas fa-trash" aria-hidden="true"></i>
                                <span class="tooltip-text">Delete user</span>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
// Select all functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const userCheckboxes = document.querySelectorAll('#users-table tbody input[type="checkbox"]');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }
    
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateBulkActions();
        });
    });
    
    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('#users-table tbody input[type="checkbox"]:checked');
        const totalBoxes = userCheckboxes.length;
        
        if (checkedBoxes.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedBoxes.length === totalBoxes) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
            selectAllCheckbox.checked = false;
        }
    }
    
    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('#users-table tbody input[type="checkbox"]:checked');
        const bulkActionsContainer = document.getElementById('bulk-actions');
        
        if (checkedBoxes.length > 0) {
            if (!bulkActionsContainer) {
                createBulkActions();
            }
        } else {
            if (bulkActionsContainer) {
                bulkActionsContainer.remove();
            }
        }
    }
    
    function createBulkActions() {
        const tableContainer = document.querySelector('.overflow-x-auto');
        const bulkActions = document.createElement('div');
        bulkActions.id = 'bulk-actions';
        bulkActions.className = 'bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4 flex items-center justify-between';
        bulkActions.innerHTML = `
            <div class="flex items-center">
                <span class="text-sm font-medium text-blue-900">
                    <span id="selected-count">0</span> users selected
                </span>
            </div>
            <div class="flex space-x-2">
                <button onclick="bulkActivate()" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors">
                    <i class="fas fa-check mr-1" aria-hidden="true"></i>
                    Activate
                </button>
                <button onclick="bulkDeactivate()" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 transition-colors">
                    <i class="fas fa-pause mr-1" aria-hidden="true"></i>
                    Deactivate
                </button>
                <button onclick="bulkDelete()" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-1" aria-hidden="true"></i>
                    Delete
                </button>
            </div>
        `;
        tableContainer.appendChild(bulkActions);
        updateSelectedCount();
    }
    
    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('#users-table tbody input[type="checkbox"]:checked');
        const countElement = document.getElementById('selected-count');
        if (countElement) {
            countElement.textContent = checkedBoxes.length;
        }
    }
});



// Bulk action functions
function bulkActivate() {
    const selectedIds = getSelectedUserIds();
    if (selectedIds.length > 0) {
        // Implement bulk activate functionality
        console.log('Activating users:', selectedIds);
        showNotification('Users activated successfully', 'success');
    }
}

function bulkDeactivate() {
    const selectedIds = getSelectedUserIds();
    if (selectedIds.length > 0) {
        // Implement bulk deactivate functionality
        console.log('Deactivating users:', selectedIds);
        showNotification('Users deactivated successfully', 'success');
    }
}

function bulkDelete() {
    const selectedIds = getSelectedUserIds();
    if (selectedIds.length > 0) {
        // Implement bulk delete functionality
        console.log('Deleting users:', selectedIds);
        showNotification('Users deleted successfully', 'success');
    }
}

function getSelectedUserIds() {
    const checkedBoxes = document.querySelectorAll('#users-table tbody input[type="checkbox"]:checked');
    return Array.from(checkedBoxes).map(checkbox => checkbox.value);
}

// Table sorting functionality
function sortTable(columnIndex) {
    const table = document.getElementById('users-table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();
        return aValue.localeCompare(bValue);
    });
    
    // Clear and re-append sorted rows
    rows.forEach(row => tbody.appendChild(row));
}

// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check' : type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>
