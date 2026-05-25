<x-admin-layout pageTitle="System Administration">

@push('styles')
<style>
    .s-card { background:#fff; border-radius:1rem; border:1px solid #f3f4f6; box-shadow:0 1px 3px rgba(0,0,0,.05); }
    .s-section-title { font-size:0.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#9ca3af; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem; }
    .s-section-title::after { content:""; flex:1; height:1px; background:#f3f4f6; }
    .nav-tab { display:flex; align-items:center; gap:.625rem; padding:.625rem .875rem; border-radius:.75rem; font-size:.8rem; font-weight:600; color:#6b7280; transition:all .15s; width:100%; text-align:left; }
    .nav-tab:hover { background:#f9fafb; color:#111827; }
    .nav-tab.active { background:#fef2f2; color:#dc2626; }
    .nav-tab.active svg { color:#dc2626; }
    .nav-tab svg { width:16px; height:16px; color:#9ca3af; flex-shrink:0; }
    .log-container {
        background-color: rgb(17 24 39);
        border-radius: 0.75rem;
        padding: 1rem;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        line-height: 1.5;
        color: rgb(229 231 235);
        max-height: 400px;
        overflow-y: auto;
    }
    .log-line { margin-bottom: 0.25rem; word-wrap: break-word; }
    .log-error { color: rgb(248 113 113); }
    .log-warning { color: rgb(251 191 36); }
    .log-info { color: rgb(96 165 250); }
    .log-success { color: rgb(74 222 128); }
    .log-debug { color: rgb(167 139 250); }
    .info-card {
        background: #f9fafb;
        border-radius: 0.75rem;
        padding: 1rem;
        border-left: 3px solid #dc2626;
    }
    .info-label { font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem; }
    .info-value { font-size: 1rem; font-weight: 700; color: #111827; }
    .backup-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
    }
    .backup-name { font-weight: 600; color: #111827; font-size: 0.875rem; }
    .backup-meta { font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem; }
    .progress-bar {
        width: 100%;
        height: 8px;
        background-color: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background-color: #dc2626;
        transition: width 0.3s ease;
    }
    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    .status-online { background-color: #22c55e; }
    .status-offline { background-color: #ef4444; }
    .status-warning { background-color: #f59e0b; }
</style>
@endpush

@php $tab = $activeTab ?? 'logs'; @endphp

<div x-data="systemAdmin()" x-init="init()" class="max-w-6xl mx-auto">

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">System Administration</h1>
            <p class="text-sm text-gray-400 mt-0.5">Monitor system health and manage maintenance</p>
        </div>
        <button @click="refreshSystemInfo()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-xl transition flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Refresh
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- Sidebar nav --}}
        <div class="lg:col-span-1">
            <div class="s-card p-3 sticky top-24">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2 mb-2">System Tools</p>
                <nav class="space-y-0.5">
                    <button @click="activeTab = 'logs'" :class="activeTab === 'logs' ? 'nav-tab active' : 'nav-tab'">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        System Logs
                    </button>
                    <button @click="activeTab = 'backup'" :class="activeTab === 'backup' ? 'nav-tab active' : 'nav-tab'">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Backup & Restore
                    </button>
                    <button @click="activeTab = 'cache'" :class="activeTab === 'cache' ? 'nav-tab active' : 'nav-tab'">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Cache Management
                    </button>
                    <button @click="activeTab = 'maintenance'" :class="activeTab === 'maintenance' ? 'nav-tab active' : 'nav-tab'">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Maintenance
                    </button>
                </nav>
            </div>
        </div>

        {{-- Content panel --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- System Status Card --}}
            <div class="s-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="s-section-title mb-0">System Status</p>
                    <div class="flex items-center gap-2">
                        <span class="status-indicator" :class="systemStatus.overall === 'online' ? 'status-online' : systemStatus.overall === 'offline' ? 'status-offline' : 'status-warning'"></span>
                        <span class="text-xs font-semibold text-gray-600" x-text="systemStatus.overall.charAt(0).toUpperCase() + systemStatus.overall.slice(1)"></span>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="info-card">
                        <div class="info-label">PHP Version</div>
                        <div class="info-value" x-text="systemInfo.phpVersion"></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Laravel Version</div>
                        <div class="info-value" x-text="systemInfo.laravelVersion"></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Database</div>
                        <div class="info-value" x-text="systemInfo.database"></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Server Uptime</div>
                        <div class="info-value" x-text="systemInfo.uptime"></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Memory Usage</div>
                        <div class="info-value" x-text="systemInfo.memoryUsage"></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Disk Usage</div>
                        <div class="info-value" x-text="systemInfo.diskUsage"></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">CPU Usage</div>
                        <div class="info-value" x-text="systemInfo.cpuUsage"></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Active Connections</div>
                        <div class="info-value" x-text="systemInfo.activeConnections"></div>
                    </div>
                </div>
            </div>

            {{-- System Logs --}}
            <div x-show="activeTab === 'logs'" class="s-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="s-section-title mb-0">System Logs</p>
                    <div class="flex gap-2">
                        <select x-model="logLevel" @change="filterLogs()" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:border-red-500">
                            <option value="all">All Levels</option>
                            <option value="error">Error</option>
                            <option value="warning">Warning</option>
                            <option value="info">Info</option>
                            <option value="debug">Debug</option>
                        </select>
                        <button @click="clearLogs()" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-3 rounded-lg text-xs transition">Clear</button>
                        <button @click="downloadLogs()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-3 rounded-lg text-xs transition">Download</button>
                    </div>
                </div>
                <div class="log-container">
                    <template x-for="log in filteredLogs" :key="log.id">
                        <div class="log-line" :class="'log-' + log.level">
                            <span x-text="log.timestamp"></span>
                            <span x-text="'[' + log.level.toUpperCase() + ']'"></span>
                            <span x-text="log.message"></span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Backup & Restore --}}
            <div x-show="activeTab === 'backup'" style="display:none;" class="space-y-4">
                <div class="s-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <p class="s-section-title mb-0">Backup & Restore</p>
                        <button @click="createBackup()" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-xl transition text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Create Backup
                        </button>
                    </div>
                    
                    {{-- Backup Progress --}}
                    <div x-show="backupProgress.show" class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Creating backup...</span>
                            <span class="text-sm text-gray-500" x-text="backupProgress.percentage + '%'"></span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" :style="'width: ' + backupProgress.percentage + '%'"></div>
                        </div>
                    </div>
                    
                    {{-- Backup List --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Recent Backups</p>
                        <template x-for="backup in backups" :key="backup.id">
                            <div class="backup-item">
                                <div class="flex-1">
                                    <div class="backup-name" x-text="backup.name"></div>
                                    <div class="backup-meta">
                                        <span x-text="backup.date + ' • ' + backup.size + ' • ' + backup.type"></span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="downloadBackup(backup)" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-1.5 px-3 rounded-lg text-xs transition">Download</button>
                                    <button @click="restoreBackup(backup)" class="bg-red-100 hover:bg-red-200 text-red-700 font-medium py-1.5 px-3 rounded-lg text-xs transition">Restore</button>
                                    <button @click="deleteBackup(backup)" class="bg-red-600 hover:bg-red-700 text-white font-medium py-1.5 px-3 rounded-lg text-xs transition">Delete</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Cache Management --}}
            <div x-show="activeTab === 'cache'" style="display:none;" class="s-card p-6">
                <p class="s-section-title">Cache Management</p>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">Application Cache</div>
                            <div class="text-xs text-gray-500">Clear application cache</div>
                        </div>
                        <button @click="clearCache('app')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg text-xs transition">Clear</button>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">Configuration Cache</div>
                            <div class="text-xs text-gray-500">Clear configuration cache</div>
                        </div>
                        <button @click="clearCache('config')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg text-xs transition">Clear</button>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">Route Cache</div>
                            <div class="text-xs text-gray-500">Clear route cache</div>
                        </div>
                        <button @click="clearCache('route')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg text-xs transition">Clear</button>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">View Cache</div>
                            <div class="text-xs text-gray-500">Clear compiled views</div>
                        </div>
                        <button @click="clearCache('view')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg text-xs transition">Clear</button>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-red-50 rounded-xl border border-red-100">
                        <div>
                            <div class="font-semibold text-red-900 text-sm">Clear All Cache</div>
                            <div class="text-xs text-red-600">Clear all cache types at once</div>
                        </div>
                        <button @click="clearAllCache()" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg text-xs transition">Clear All</button>
                    </div>
                </div>
            </div>

            {{-- Maintenance --}}
            <div x-show="activeTab === 'maintenance'" style="display:none;" class="s-card p-6">
                <p class="s-section-title">Maintenance Tools</p>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">Optimize Database</div>
                            <div class="text-xs text-gray-500">Optimize database tables for better performance</div>
                        </div>
                        <button @click="optimizeDatabase()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg text-xs transition">Optimize</button>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">Clean Up Sessions</div>
                            <div class="text-xs text-gray-500">Remove expired user sessions</div>
                        </div>
                        <button @click="cleanupSessions()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg text-xs transition">Clean Up</button>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">Clean Up Logs</div>
                            <div class="text-xs text-gray-500">Remove old system log files</div>
                        </div>
                        <button @click="cleanupLogs()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg text-xs transition">Clean Up</button>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 rounded-xl border" :class="maintenanceMode ? 'bg-amber-50 border-amber-200' : 'bg-red-50 border-red-200'">
                        <div>
                            <div class="font-semibold text-sm" :class="maintenanceMode ? 'text-amber-900' : 'text-red-900'">Maintenance Mode</div>
                            <div class="text-xs" :class="maintenanceMode ? 'text-amber-600' : 'text-red-600'" x-text="maintenanceMode ? 'Site is currently in maintenance mode' : 'Enable to put site in maintenance mode'"></div>
                        </div>
                        <button @click="toggleMaintenanceMode()" :class="maintenanceMode ? 'bg-amber-600 hover:bg-amber-700' : 'bg-red-600 hover:bg-red-700'" class="text-white font-semibold py-2 px-4 rounded-lg text-xs transition" x-text="maintenanceMode ? 'Disable' : 'Enable'"></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function systemAdmin() {
    return {
        activeTab: 'logs',
        logLevel: 'all',
        maintenanceMode: false,
        systemStatus: {
            overall: 'online'
        },
        systemInfo: {
            phpVersion: '8.2.15',
            lararavelVersion: '10.45.1',
            database: 'MySQL 8.0.33',
            uptime: '15 days, 7 hours',
            memoryUsage: '256 MB / 2 GB',
            diskUsage: '45.2 GB / 100 GB',
            cpuUsage: '12.5%',
            activeConnections: '24'
        },
        logs: [],
        filteredLogs: [],
        backups: [],
        backupProgress: {
            show: false,
            percentage: 0
        },
        
        init() {
            this.loadSystemLogs();
            this.loadBackups();
            this.checkMaintenanceMode();
        },
        
        async loadSystemLogs() {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 500));
            
            this.logs = [
                { id: 1, timestamp: '2024-05-24 22:30:15', level: 'info', message: 'Application started successfully' },
                { id: 2, timestamp: '2024-05-24 22:30:16', level: 'info', message: 'Database connection established' },
                { id: 3, timestamp: '2024-05-24 22:30:17', level: 'info', message: 'Cache initialized' },
                { id: 4, timestamp: '2024-05-24 22:30:20', level: 'warning', message: 'High memory usage detected: 85%' },
                { id: 5, timestamp: '2024-05-24 22:30:25', level: 'error', message: 'Failed to send email notification' },
                { id: 6, timestamp: '2024-05-24 22:30:30', level: 'info', message: 'User login: admin@example.com' },
                { id: 7, timestamp: '2024-05-24 22:30:35', level: 'debug', message: 'Processing booking #12345' },
                { id: 8, timestamp: '2024-05-24 22:30:40', level: 'info', message: 'Booking #12345 completed successfully' },
                { id: 9, timestamp: '2024-05-24 22:30:45', level: 'warning', message: 'Disk space running low: 90% used' },
                { id: 10, timestamp: '2024-05-24 22:30:50', level: 'info', message: 'Scheduled backup completed' }
            ];
            
            this.filterLogs();
        },
        
        async loadBackups() {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 500));
            
            this.backups = [
                { id: 1, name: 'flyoverbd_backup_2024_05_24_22_00.sql', date: '2024-05-24 22:00:00', size: '45.2 MB', type: 'Full Backup' },
                { id: 2, name: 'flyoverbd_backup_2024_05_23_22_00.sql', date: '2024-05-23 22:00:00', size: '44.8 MB', type: 'Full Backup' },
                { id: 3, name: 'flyoverbd_backup_2024_05_22_22_00.sql', date: '2024-05-22 22:00:00', size: '44.1 MB', type: 'Full Backup' },
                { id: 4, name: 'flyoverdb_files_2024_05_24_22_00.zip', date: '2024-05-24 22:00:00', size: '125.3 MB', type: 'Files Backup' }
            ];
        },
        
        filterLogs() {
            if (this.logLevel === 'all') {
                this.filteredLogs = [...this.logs];
            } else {
                this.filteredLogs = this.logs.filter(log => log.level === this.logLevel);
            }
        },
        
        async clearLogs() {
            if (confirm('Are you sure you want to clear all system logs?')) {
                this.logs = [];
                this.filteredLogs = [];
            }
        },
        
        downloadLogs() {
            // Simulate download
            alert('Log file download would be implemented here');
        },
        
        async createBackup() {
            this.backupProgress.show = true;
            this.backupProgress.percentage = 0;
            
            // Simulate backup progress
            for (let i = 0; i <= 100; i += 10) {
                await new Promise(resolve => setTimeout(resolve, 200));
                this.backupProgress.percentage = i;
            }
            
            this.backupProgress.show = false;
            
            // Add new backup to list
            const newBackup = {
                id: this.backups.length + 1,
                name: `flyoverbd_backup_${new Date().toISOString().slice(0, 19).replace(/:/g, '_')}.sql`,
                date: new Date().toLocaleString(),
                size: '45.5 MB',
                type: 'Full Backup'
            };
            
            this.backups.unshift(newBackup);
        },
        
        downloadBackup(backup) {
            alert(`Download ${backup.name} would be implemented here`);
        },
        
        async restoreBackup(backup) {
            if (confirm(`Are you sure you want to restore ${backup.name}? This will overwrite current data.`)) {
                alert(`Restore ${backup.name} would be implemented here`);
            }
        },
        
        async deleteBackup(backup) {
            if (confirm(`Are you sure you want to delete ${backup.name}?`)) {
                const index = this.backups.findIndex(b => b.id === backup.id);
                if (index !== -1) {
                    this.backups.splice(index, 1);
                }
            }
        },
        
        async clearCache(type) {
            alert(`Clear ${type} cache would be implemented here`);
        },
        
        async clearAllCache() {
            if (confirm('Are you sure you want to clear all cache?')) {
                alert('Clear all cache would be implemented here');
            }
        },
        
        async optimizeDatabase() {
            alert('Database optimization would be implemented here');
        },
        
        async cleanupSessions() {
            if (confirm('Are you sure you want to clean up expired sessions?')) {
                alert('Session cleanup would be implemented here');
            }
        },
        
        async cleanupLogs() {
            if (confirm('Are you sure you want to clean up old log files?')) {
                alert('Log cleanup would be implemented here');
            }
        },
        
        async toggleMaintenanceMode() {
            this.maintenanceMode = !this.maintenanceMode;
            alert(`Maintenance mode ${this.maintenanceMode ? 'enabled' : 'disabled'} would be implemented here`);
        },
        
        async checkMaintenanceMode() {
            // Check current maintenance mode status
            this.maintenanceMode = false;
        },
        
        async refreshSystemInfo() {
            // Simulate refreshing system info
            await new Promise(resolve => setTimeout(resolve, 500));
            
            this.systemInfo.uptime = '15 days, 8 hours';
            this.systemInfo.memoryUsage = '268 MB / 2 GB';
            this.systemInfo.cpuUsage = '15.2%';
            this.systemInfo.activeConnections = '27';
        }
    }
}
</script>
@endpush
</x-admin-layout>
