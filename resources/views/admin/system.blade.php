<x-admin-layout pageTitle="System Administration">

@push('styles')
    <style>
        .system-card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid rgb(243 244 246);
            padding: 1.5rem;
        }
        .system-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgb(229 231 235);
        }
        .log-container {
            background-color: rgb(17 24 39);
            border-radius: 0.5rem;
            padding: 1rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            line-height: 1.5;
            color: rgb(229 231 235);
            max-height: 400px;
            overflow-y: auto;
        }
        .log-line {
            margin-bottom: 0.25rem;
            word-wrap: break-word;
        }
        .log-error {
            color: rgb(248 113 113);
        }
        .log-warning {
            color: rgb(251 191 36);
        }
        .log-info {
            color: rgb(96 165 250);
        }
        .log-success {
            color: rgb(74 222 128);
        }
        .log-debug {
            color: rgb(167 139 250);
        }
        .btn {
            padding: 0.75rem 1.5rem;
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
        .btn-success {
            background-color: rgb(34 197 94);
            color: white;
        }
        .btn-success:hover {
            background-color: rgb(22 163 74);
        }
        .btn-danger {
            background-color: rgb(239 68 68);
            color: white;
        }
        .btn-danger:hover {
            background-color: rgb(220 38 38);
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }
        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: rgb(229 231 235);
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background-color: rgb(59 130 246);
            transition: width 0.3s ease;
        }
        .system-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        .info-item {
            padding: 1rem;
            background-color: rgb(249 250 251);
            border-radius: 0.5rem;
            border-left: 4px solid rgb(59 130 246);
        }
        .info-label {
            font-size: 0.875rem;
            color: rgb(107 114 128);
            margin-bottom: 0.25rem;
        }
        .info-value {
            font-size: 1.125rem;
            font-weight: 600;
            color: rgb(17 24 39);
        }
        .backup-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border: 1px solid rgb(229 231 235);
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .backup-info {
            flex: 1;
        }
        .backup-name {
            font-weight: 500;
            color: rgb(17 24 39);
        }
        .backup-meta {
            font-size: 0.875rem;
            color: rgb(107 114 128);
            margin-top: 0.25rem;
        }
        .backup-actions {
            display: flex;
            gap: 0.5rem;
        }
        .tabs {
            display: flex;
            border-bottom: 1px solid rgb(229 231 235);
            margin-bottom: 2rem;
        }
        .tab {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: rgb(107 114 128);
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
        }
        .tab:hover {
            color: rgb(55 65 81);
        }
        .tab.active {
            color: rgb(59 130 246);
            border-bottom-color: rgb(59 130 246);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }
        .status-online {
            background-color: rgb(34 197 94);
        }
        .status-offline {
            background-color: rgb(239 68 68);
        }
        .status-warning {
            background-color: rgb(251 191 36);
        }
        
        /* Responsive */
        @media (max-width: 767px) {
            .system-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            .backup-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .backup-actions {
                width: 100%;
                justify-content: flex-end;
            }
            .tabs {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
@endpush

<div x-data="systemAdmin()" class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System Administration</h1>
            <p class="text-gray-600 mt-1">Monitor system health and manage backups</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="refreshSystemInfo()" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- System Status -->
    <div class="system-card">
        <div class="system-header">
            <h2 class="text-xl font-semibold text-gray-900">System Status</h2>
            <div class="flex items-center gap-2">
                <span class="status-indicator" :class="systemStatus.overall === 'online' ? 'status-online' : systemStatus.overall === 'offline' ? 'status-offline' : 'status-warning'"></span>
                <span class="text-sm font-medium" x-text="systemStatus.overall.charAt(0).toUpperCase() + systemStatus.overall.slice(1)"></span>
            </div>
        </div>
        
        <div class="system-info">
            <div class="info-item">
                <div class="info-label">PHP Version</div>
                <div class="info-value" x-text="systemInfo.phpVersion"></div>
            </div>
            <div class="info-item">
                <div class="info-label">Laravel Version</div>
                <div class="info-value" x-text="systemInfo.laravelVersion"></div>
            </div>
            <div class="info-item">
                <div class="info-label">Database</div>
                <div class="info-value" x-text="systemInfo.database"></div>
            </div>
            <div class="info-item">
                <div class="info-label">Server Uptime</div>
                <div class="info-value" x-text="systemInfo.uptime"></div>
            </div>
            <div class="info-item">
                <div class="info-label">Memory Usage</div>
                <div class="info-value" x-text="systemInfo.memoryUsage"></div>
            </div>
            <div class="info-item">
                <div class="info-label">Disk Usage</div>
                <div class="info-value" x-text="systemInfo.diskUsage"></div>
            </div>
            <div class="info-item">
                <div class="info-label">CPU Usage</div>
                <div class="info-value" x-text="systemInfo.cpuUsage"></div>
            </div>
            <div class="info-item">
                <div class="info-label">Active Connections</div>
                <div class="info-value" x-text="systemInfo.activeConnections"></div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button @click="activeTab = 'logs'" :class="{ 'active': activeTab === 'logs' }" class="tab">System Logs</button>
        <button @click="activeTab = 'backup'" :class="{ 'active': activeTab === 'backup' }" class="tab">Backup & Restore</button>
        <button @click="activeTab = 'cache'" :class="{ 'active': activeTab === 'cache' }" class="tab">Cache Management</button>
        <button @click="activeTab = 'maintenance'" :class="{ 'active': activeTab === 'maintenance' }" class="tab">Maintenance</button>
    </div>

    <!-- System Logs -->
    <div x-show="activeTab === 'logs'" class="tab-content active">
        <div class="system-card">
            <div class="system-header">
                <h2 class="text-xl font-semibold text-gray-900">System Logs</h2>
                <div class="flex gap-2">
                    <select x-model="logLevel" @change="filterLogs()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="all">All Levels</option>
                        <option value="error">Error</option>
                        <option value="warning">Warning</option>
                        <option value="info">Info</option>
                        <option value="debug">Debug</option>
                    </select>
                    <button @click="clearLogs()" class="btn btn-sm btn-danger">Clear Logs</button>
                    <button @click="downloadLogs()" class="btn btn-sm btn-secondary">Download</button>
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
    </div>

    <!-- Backup & Restore -->
    <div x-show="activeTab === 'backup'" class="tab-content">
        <div class="system-card">
            <div class="system-header">
                <h2 class="text-xl font-semibold text-gray-900">Backup & Restore</h2>
                <button @click="createBackup()" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Create Backup
                </button>
            </div>
            
            <!-- Backup Progress -->
            <div x-show="backupProgress.show" class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium">Creating backup...</span>
                    <span class="text-sm" x-text="backupProgress.percentage + '%'"></span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" :style="'width: ' + backupProgress.percentage + '%'"></div>
                </div>
            </div>
            
            <!-- Backup List -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Backups</h3>
                <template x-for="backup in backups" :key="backup.id">
                    <div class="backup-item">
                        <div class="backup-info">
                            <div class="backup-name" x-text="backup.name"></div>
                            <div class="backup-meta">
                                <span x-text="backup.date + ' • ' + backup.size + ' • ' + backup.type"></span>
                            </div>
                        </div>
                        <div class="backup-actions">
                            <button @click="downloadBackup(backup)" class="btn btn-sm btn-secondary">Download</button>
                            <button @click="restoreBackup(backup)" class="btn btn-sm btn-primary">Restore</button>
                            <button @click="deleteBackup(backup)" class="btn btn-sm btn-danger">Delete</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Cache Management -->
    <div x-show="activeTab === 'cache'" class="tab-content">
        <div class="system-card">
            <div class="system-header">
                <h2 class="text-xl font-semibold text-gray-900">Cache Management</h2>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium">Application Cache</div>
                        <div class="text-sm text-gray-600">Clear application cache</div>
                    </div>
                    <button @click="clearCache('app')" class="btn btn-secondary">Clear</button>
                </div>
                
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium">Configuration Cache</div>
                        <div class="text-sm text-gray-600">Clear configuration cache</div>
                    </div>
                    <button @click="clearCache('config')" class="btn btn-secondary">Clear</button>
                </div>
                
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium">Route Cache</div>
                        <div class="text-sm text-gray-600">Clear route cache</div>
                    </div>
                    <button @click="clearCache('route')" class="btn btn-secondary">Clear</button>
                </div>
                
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium">View Cache</div>
                        <div class="text-sm text-gray-600">Clear compiled views</div>
                    </div>
                    <button @click="clearCache('view')" class="btn btn-secondary">Clear</button>
                </div>
                
                <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div>
                        <div class="font-medium text-blue-900">Clear All Cache</div>
                        <div class="text-sm text-blue-600">Clear all cache types</div>
                    </div>
                    <button @click="clearAllCache()" class="btn btn-primary">Clear All</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance -->
    <div x-show="activeTab === 'maintenance'" class="tab-content">
        <div class="system-card">
            <div class="system-header">
                <h2 class="text-xl font-semibold text-gray-900">Maintenance Tools</h2>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium">Optimize Database</div>
                        <div class="text-sm text-gray-600">Optimize database tables</div>
                    </div>
                    <button @click="optimizeDatabase()" class="btn btn-secondary">Optimize</button>
                </div>
                
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium">Clean Up Sessions</div>
                        <div class="text-sm text-gray-600">Remove expired sessions</div>
                    </div>
                    <button @click="cleanupSessions()" class="btn btn-secondary">Clean Up</button>
                </div>
                
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium">Clean Up Logs</div>
                        <div class="text-sm text-gray-600">Remove old log files</div>
                    </div>
                    <button @click="cleanupLogs()" class="btn btn-secondary">Clean Up</button>
                </div>
                
                <div class="flex justify-between items-center p-4 bg-red-50 rounded-lg border border-red-200">
                    <div>
                        <div class="font-medium text-red-900">Maintenance Mode</div>
                        <div class="text-sm text-red-600">Enable/disable maintenance mode</div>
                    </div>
                    <button @click="toggleMaintenanceMode()" :class="maintenanceMode ? 'btn btn-success' : 'btn btn-danger'" x-text="maintenanceMode ? 'Disable' : 'Enable'"></button>
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
