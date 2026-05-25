<x-admin-layout pageTitle="User Management">

@push('styles')
    <style>
        .user-card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid rgb(243 244 246);
            padding: 1.5rem;
        }
        .table-container {
            overflow-x: auto;
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container th,
        .table-container td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid rgb(229 231 235);
        }
        .table-container th {
            background-color: rgb(249 250 251);
            font-weight: 600;
            color: rgb(55 65 81);
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-success {
            background-color: rgb(34 197 94);
            color: white;
        }
        .badge-warning {
            background-color: rgb(251 146 60);
            color: white;
        }
        .badge-danger {
            background-color: rgb(239 68 68);
            color: white;
        }
        .badge-gray {
            background-color: rgb(107 114 128);
            color: white;
        }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary {
            background-color: rgb(59 130 246);
            color: white;
        }
        .btn-primary:hover {
            background-color: rgb(37 99 235);
        }
        .btn-secondary {
            background-color: rgb(107 114 128);
            color: white;
        }
        .btn-secondary:hover {
            background-color: rgb(75 85 99);
        }
        .btn-danger {
            background-color: rgb(239 68 68);
            color: white;
        }
        .btn-danger:hover {
            background-color: rgb(220 38 38);
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgb(229 231 235);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: rgb(55 65 81);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
        }
        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: white;
            border-radius: 0.75rem;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: rgb(55 65 81);
        }
        .form-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid rgb(209 213 219);
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        .form-input:focus {
            outline: none;
            border-color: rgb(59 130 246);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Responsive */
        @media (max-width: 767px) {
            .table-container {
                margin: 0 -1rem;
                padding: 0 1rem;
            }
            .user-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

<div x-data="userManagement()" class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
            <p class="text-gray-600 mt-1">Manage system users and permissions</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="showAddUserModal = true" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add User
            </button>
            <button @click="refreshUsers()" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2.5 px-5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="user-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-gray-900" x-text="userStats.total"></div>
                    <div class="text-sm text-gray-600">Total Users</div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="user-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-gray-900" x-text="userStats.active"></div>
                    <div class="text-sm text-gray-600">Active Users</div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="user-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-gray-900" x-text="userStats.admins"></div>
                    <div class="text-sm text-gray-600">Admin Users</div>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="user-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-gray-900" x-text="userStats.newThisMonth"></div>
                    <div class="text-sm text-gray-600">New This Month</div>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="user-card">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    x-model="searchTerm" 
                    @input="filterUsers()"
                    placeholder="Search users by name or email..." 
                    class="form-input"
                >
            </div>
            <select x-model="roleFilter" @change="filterUsers()" class="form-input md:w-48">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
                <option value="manager">Manager</option>
            </select>
            <select x-model="statusFilter" @change="filterUsers()" class="form-input md:w-48">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="user-card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Last Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="user in filteredUsers" :key="user.id">
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="user-avatar" x-text="user.name.charAt(0).toUpperCase()"></div>
                                    <div>
                                        <div class="font-medium text-gray-900" x-text="user.name"></div>
                                        <div class="text-xs text-gray-500" x-text="'ID: ' + user.id"></div>
                                    </div>
                                </div>
                            </td>
                            <td x-text="user.email"></td>
                            <td>
                                <span class="badge" :class="{
                                    'badge-danger': user.role === 'admin',
                                    'badge-warning': user.role === 'manager',
                                    'badge-gray': user.role === 'user'
                                }" x-text="user.role.charAt(0).toUpperCase() + user.role.slice(1)"></span>
                            </td>
                            <td>
                                <span class="badge" :class="{
                                    'badge-success': user.status === 'active',
                                    'badge-warning': user.status === 'inactive',
                                    'badge-danger': user.status === 'suspended'
                                }" x-text="user.status.charAt(0).toUpperCase() + user.status.slice(1)"></span>
                            </td>
                            <td x-text="user.joinedDate"></td>
                            <td x-text="user.lastActive"></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <button @click="editUser(user)" class="btn btn-sm btn-secondary">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="toggleUserStatus(user)" 
                                            :class="user.status === 'active' ? 'btn btn-sm btn-warning' : 'btn btn-sm btn-success'"
                                            x-text="user.status === 'active' ? 'Deactivate' : 'Activate'">
                                    </button>
                                    <button @click="deleteUser(user)" class="btn btn-sm btn-danger">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal" :class="{ 'show': showAddUserModal || showEditUserModal }">
        <div class="modal-content">
            <h2 class="text-xl font-bold text-gray-900 mb-4" x-text="showEditUserModal ? 'Edit User' : 'Add New User'"></h2>
            <form @submit.prevent="saveUser()">
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" x-model="currentUser.name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" x-model="currentUser.email" class="form-input" required>
                </div>
                <div class="form-group" x-show="!showEditUserModal">
                    <label class="form-label">Password</label>
                    <input type="password" x-model="currentUser.password" class="form-input" x-bind:required="!showEditUserModal">
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select x-model="currentUser.role" class="form-input" required>
                        <option value="user">User</option>
                        <option value="manager">Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select x-model="currentUser.status" class="form-input" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="closeModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
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
