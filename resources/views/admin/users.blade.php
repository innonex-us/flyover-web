<x-admin-layout pageTitle="User Management">

@push('styles')
    <style>
        /* Stats Cards */
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            padding: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        }
        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Table Styles */
        .users-table-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .users-table th {
            background: #f8fafc;
            padding: 1rem 1.25rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }
        .users-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .users-table tr:hover td {
            background: #f8fafc;
        }
        .users-table tr:last-child td {
            border-bottom: none;
        }
        
        /* Avatar */
        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #C8102E 0%, #a00d26 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            font-size: 0.875rem;
        }
        
        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        .badge-admin {
            background: #fef2f2;
            color: #dc2626;
        }
        .badge-admin::before { background: #dc2626; }
        .badge-manager {
            background: #fff7ed;
            color: #ea580c;
        }
        .badge-manager::before { background: #ea580c; }
        .badge-user {
            background: #f1f5f9;
            color: #64748b;
        }
        .badge-user::before { background: #64748b; }
        .badge-active {
            background: #f0fdf4;
            color: #16a34a;
        }
        .badge-active::before { background: #16a34a; }
        .badge-inactive {
            background: #fefce8;
            color: #ca8a04;
        }
        .badge-inactive::before { background: #ca8a04; }
        .badge-suspended {
            background: #fef2f2;
            color: #dc2626;
        }
        .badge-suspended::before { background: #dc2626; }
        
        /* Action Buttons */
        .action-btn {
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .action-btn-edit {
            background: #f1f5f9;
            color: #475569;
        }
        .action-btn-edit:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .action-btn-toggle {
            background: #f0fdf4;
            color: #16a34a;
        }
        .action-btn-toggle:hover {
            background: #dcfce7;
        }
        .action-btn-toggle.inactive {
            background: #fefce8;
            color: #ca8a04;
        }
        .action-btn-toggle.inactive:hover {
            background: #fef9c3;
        }
        .action-btn-delete {
            background: #fef2f2;
            color: #dc2626;
        }
        .action-btn-delete:hover {
            background: #fee2e2;
        }
        
        /* Filter Bar */
        .filter-bar {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            padding: 1rem 1.25rem;
        }
        .search-input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: #f8fafc;
        }
        .search-input:focus {
            outline: none;
            border-color: #C8102E;
            background: white;
            box-shadow: 0 0 0 3px rgba(200, 16, 46, 0.1);
        }
        .filter-select {
            padding: 0.625rem 2.5rem 0.625rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            background: #f8fafc;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.25rem;
        }
        .filter-select:focus {
            outline: none;
            border-color: #C8102E;
            box-shadow: 0 0 0 3px rgba(200, 16, 46, 0.1);
        }
        
        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 50;
            padding: 1rem;
        }
        .modal-overlay.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-panel {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 28rem;
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
        }
        .modal-header {
            padding: 1.5rem 1.5rem 0;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-footer {
            padding: 0 1.5rem 1.5rem;
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }
        .form-input {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #C8102E;
            box-shadow: 0 0 0 3px rgba(200, 16, 46, 0.1);
        }
        
        /* Buttons */
        .btn-primary {
            background: #C8102E;
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary:hover {
            background: #a00d26;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(200, 16, 46, 0.3);
        }
        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-secondary:hover {
            background: #e2e8f0;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
        }
        .empty-icon {
            width: 4rem;
            height: 4rem;
            background: #f1f5f9;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .users-table-container {
                overflow-x: auto;
            }
        }
        @media (max-width: 640px) {
            .stat-card {
                padding: 1rem;
            }
        }
    </style>
@endpush

<div x-data="userManagement()" class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
            <p class="text-gray-500 mt-1">Manage system users and their permissions</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="showAddUserModal = true" class="btn-primary py-2.5 px-5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add User
            </button>
            <button @click="refreshUsers()" class="btn-secondary py-2.5 px-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-gray-900" x-text="userStats.total"></div>
                    <div class="text-sm text-gray-500 mt-0.5">Total Users</div>
                </div>
                <div class="stat-icon bg-blue-50 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-gray-900" x-text="userStats.active"></div>
                    <div class="text-sm text-gray-500 mt-0.5">Active Users</div>
                </div>
                <div class="stat-icon bg-green-50 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-gray-900" x-text="userStats.admins"></div>
                    <div class="text-sm text-gray-500 mt-0.5">Admins</div>
                </div>
                <div class="stat-icon bg-red-50 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-gray-900" x-text="userStats.newThisMonth"></div>
                    <div class="text-sm text-gray-500 mt-0.5">New This Month</div>
                </div>
                <div class="stat-icon bg-orange-50 text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filter-bar">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input 
                    type="text" 
                    x-model="searchTerm" 
                    @input="filterUsers()"
                    placeholder="Search users by name or email..." 
                    class="search-input"
                >
            </div>
            <select x-model="roleFilter" @change="filterUsers()" class="filter-select md:w-40">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
                <option value="manager">Manager</option>
            </select>
            <select x-model="statusFilter" @change="filterUsers()" class="filter-select md:w-40">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="users-table-container">
        <div class="overflow-x-auto">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Last Active</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="user in filteredUsers" :key="user.id">
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="user-avatar" x-text="user.name.charAt(0).toUpperCase()"></div>
                                    <div>
                                        <div class="font-semibold text-gray-900" x-text="user.name"></div>
                                        <div class="text-xs text-gray-400" x-text="'ID: ' + user.id"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-gray-600" x-text="user.email"></td>
                            <td>
                                <span class="badge" :class="{
                                    'badge-admin': user.role === 'admin',
                                    'badge-manager': user.role === 'manager',
                                    'badge-user': user.role === 'user'
                                }" x-text="user.role.charAt(0).toUpperCase() + user.role.slice(1)"></span>
                            </td>
                            <td>
                                <span class="badge" :class="{
                                    'badge-active': user.status === 'active',
                                    'badge-inactive': user.status === 'inactive',
                                    'badge-suspended': user.status === 'suspended'
                                }" x-text="user.status.charAt(0).toUpperCase() + user.status.slice(1)"></span>
                            </td>
                            <td class="text-gray-500 text-sm" x-text="user.joinedDate"></td>
                            <td class="text-gray-500 text-sm" x-text="user.lastActive"></td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="editUser(user)" class="action-btn action-btn-edit" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="toggleUserStatus(user)" 
                                            :class="user.status === 'active' ? 'action-btn action-btn-toggle' : 'action-btn action-btn-toggle inactive'"
                                            :title="user.status === 'active' ? 'Deactivate' : 'Activate'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path x-show="user.status === 'active'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            <path x-show="user.status !== 'active'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                    <button @click="deleteUser(user)" class="action-btn action-btn-delete" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <!-- Empty State -->
        <div x-show="filteredUsers.length === 0" class="empty-state" x-cloak>
            <div class="empty-icon">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No users found</h3>
            <p class="text-gray-500 mt-1">Try adjusting your search or filters</p>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal-overlay" :class="{ 'show': showAddUserModal || showEditUserModal }" @click.self="closeModal()">
        <div class="modal-panel">
            <div class="modal-header">
                <h2 class="text-xl font-bold text-gray-900" x-text="showEditUserModal ? 'Edit User' : 'Add New User'"></h2>
                <p class="text-sm text-gray-500 mt-1" x-text="showEditUserModal ? 'Update user details and permissions' : 'Create a new user account'"></p>
            </div>
            <form @submit.prevent="saveUser()">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" x-model="currentUser.name" class="form-input" placeholder="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" x-model="currentUser.email" class="form-input" placeholder="john@example.com" required>
                    </div>
                    <div class="form-group" x-show="!showEditUserModal">
                        <label class="form-label">Password</label>
                        <input type="password" x-model="currentUser.password" class="form-input" placeholder="••••••••" x-bind:required="!showEditUserModal">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group mb-0">
                            <label class="form-label">Role</label>
                            <select x-model="currentUser.role" class="form-input" required>
                                <option value="user">User</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Status</label>
                            <select x-model="currentUser.status" class="form-input" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="closeModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function userManagement() {
    return {
        users: [],
        filteredUsers: [],
        searchTerm: '',
        roleFilter: '',
        statusFilter: '',
        showAddUserModal: false,
        showEditUserModal: false,
        currentUser: {
            id: null,
            name: '',
            email: '',
            password: '',
            role: 'user',
            status: 'active'
        },
        userStats: {
            total: 0,
            active: 0,
            admins: 0,
            newThisMonth: 0
        },
        
        init() {
            this.loadUsers();
        },
        
        async loadUsers() {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 500));
            
            this.users = [
                { id: 1, name: 'Admin User', email: 'admin@flyoverbd.com', role: 'admin', status: 'active', joinedDate: '2024-01-01', lastActive: '2 hours ago' },
                { id: 2, name: 'John Doe', email: 'john@example.com', role: 'user', status: 'active', joinedDate: '2024-01-15', lastActive: '1 day ago' },
                { id: 3, name: 'Jane Smith', email: 'jane@example.com', role: 'manager', status: 'active', joinedDate: '2024-02-01', lastActive: '3 hours ago' },
                { id: 4, name: 'Bob Johnson', email: 'bob@example.com', role: 'user', status: 'inactive', joinedDate: '2024-02-15', lastActive: '1 week ago' },
                { id: 5, name: 'Alice Brown', email: 'alice@example.com', role: 'user', status: 'suspended', joinedDate: '2024-03-01', lastActive: '2 weeks ago' },
                { id: 6, name: 'Charlie Wilson', email: 'charlie@example.com', role: 'user', status: 'active', joinedDate: '2024-03-15', lastActive: '5 minutes ago' },
                { id: 7, name: 'Diana Prince', email: 'diana@example.com', role: 'manager', status: 'active', joinedDate: '2024-04-01', lastActive: '1 hour ago' },
                { id: 8, name: 'Edward Norton', email: 'edward@example.com', role: 'user', status: 'active', joinedDate: '2024-04-15', lastActive: '30 minutes ago' }
            ];
            
            this.filteredUsers = [...this.users];
            this.updateStats();
        },
        
        updateStats() {
            this.userStats.total = this.users.length;
            this.userStats.active = this.users.filter(u => u.status === 'active').length;
            this.userStats.admins = this.users.filter(u => u.role === 'admin').length;
            this.userStats.newThisMonth = this.users.filter(u => {
                const joinedDate = new Date(u.joinedDate);
                const now = new Date();
                return joinedDate.getMonth() === now.getMonth() && joinedDate.getFullYear() === now.getFullYear();
            }).length;
        },
        
        filterUsers() {
            this.filteredUsers = this.users.filter(user => {
                const matchesSearch = user.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                     user.email.toLowerCase().includes(this.searchTerm.toLowerCase());
                const matchesRole = !this.roleFilter || user.role === this.roleFilter;
                const matchesStatus = !this.statusFilter || user.status === this.statusFilter;
                
                return matchesSearch && matchesRole && matchesStatus;
            });
        },
        
        editUser(user) {
            this.currentUser = { ...user };
            this.showEditUserModal = true;
        },
        
        async saveUser() {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 500));
            
            if (this.showEditUserModal) {
                // Update existing user
                const index = this.users.findIndex(u => u.id === this.currentUser.id);
                if (index !== -1) {
                    this.users[index] = { ...this.currentUser };
                }
            } else {
                // Add new user
                const newUser = {
                    ...this.currentUser,
                    id: Math.max(...this.users.map(u => u.id)) + 1,
                    joinedDate: new Date().toISOString().split('T')[0],
                    lastActive: 'Just now'
                };
                this.users.push(newUser);
            }
            
            this.filterUsers();
            this.updateStats();
            this.closeModal();
        },
        
        async toggleUserStatus(user) {
            const newStatus = user.status === 'active' ? 'inactive' : 'active';
            user.status = newStatus;
            this.filterUsers();
            this.updateStats();
        },
        
        async deleteUser(user) {
            if (confirm(`Are you sure you want to delete ${user.name}?`)) {
                const index = this.users.findIndex(u => u.id === user.id);
                if (index !== -1) {
                    this.users.splice(index, 1);
                    this.filterUsers();
                    this.updateStats();
                }
            }
        },
        
        closeModal() {
            this.showAddUserModal = false;
            this.showEditUserModal = false;
            this.currentUser = {
                id: null,
                name: '',
                email: '',
                password: '',
                role: 'user',
                status: 'active'
            };
        },
        
        refreshUsers() {
            this.loadUsers();
        }
    }
}
</script>
@endpush
</x-admin-layout>
