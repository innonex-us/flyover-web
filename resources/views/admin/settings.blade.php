<x-admin-layout pageTitle="System Settings">

@push('styles')
    <style>
        .settings-card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid rgb(243 244 246);
            padding: 1.5rem;
        }
        .settings-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgb(229 231 235);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: rgb(55 65 81);
        }
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgb(209 213 219);
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: rgb(59 130 246);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
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
        .btn-danger {
            background-color: rgb(239 68 68);
            color: white;
        }
        .btn-danger:hover {
            background-color: rgb(220 38 38);
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
        .switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 24px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgb(209 213 219);
            transition: 0.3s;
            border-radius: 24px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: rgb(59 130 246);
        }
        input:checked + .slider:before {
            transform: translateX(24px);
        }
        .notification {
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .notification-success {
            background-color: rgb(34 197 94);
            color: white;
        }
        .notification-error {
            background-color: rgb(239 68 68);
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 767px) {
            .tabs {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .settings-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
        }
    </style>
@endpush

<div x-data="settingsManager()" class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
            <p class="text-gray-600 mt-1">Configure your application settings</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="resetSettings()" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset
            </button>
            <button @click="saveAllSettings()" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save All
            </button>
        </div>
    </div>

    <!-- Notification -->
    <div x-show="notification.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="notification"
         :class="notification.type === 'success' ? 'notification-success' : 'notification-error'"
         x-text="notification.message">
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button @click="activeTab = 'general'" :class="{ 'active': activeTab === 'general' }" class="tab">General</button>
        <button @click="activeTab = 'email'" :class="{ 'active': activeTab === 'email' }" class="tab">Email</button>
        <button @click="activeTab = 'security'" :class="{ 'active': activeTab === 'security' }" class="tab">Security</button>
        <button @click="activeTab = 'payment'" :class="{ 'active': activeTab === 'payment' }" class="tab">Payment</button>
        <button @click="activeTab = 'social'" :class="{ 'active': activeTab === 'social' }" class="tab">Social</button>
    </div>

    <!-- General Settings -->
    <div x-show="activeTab === 'general'" class="tab-content active">
        <div class="settings-card">
            <div class="settings-header">
                <h2 class="text-xl font-semibold text-gray-900">General Settings</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label">Application Name</label>
                    <input type="text" x-model="settings.general.appName" class="form-input" placeholder="FlyoverBD">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Application URL</label>
                    <input type="url" x-model="settings.general.appUrl" class="form-input" placeholder="https://flyoverbd.com">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Default Language</label>
                    <select x-model="settings.general.language" class="form-input form-select">
                        <option value="en">English</option>
                        <option value="bn">Bangla</option>
                        <option value="ar">Arabic</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Timezone</label>
                    <select x-model="settings.general.timezone" class="form-input form-select">
                        <option value="Asia/Dhaka">Asia/Dhaka (UTC+6)</option>
                        <option value="UTC">UTC (UTC+0)</option>
                        <option value="America/New_York">America/New_York (UTC-5)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Contact Email</label>
                    <input type="email" x-model="settings.general.contactEmail" class="form-input" placeholder="info@flyoverbd.com">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Contact Phone</label>
                    <input type="tel" x-model="settings.general.contactPhone" class="form-input" placeholder="+8801234567890">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Site Description</label>
                <textarea x-model="settings.general.siteDescription" class="form-input form-textarea" placeholder="Describe your travel business..."></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Maintenance Mode</label>
                <div class="flex items-center gap-3">
                    <label class="switch">
                        <input type="checkbox" x-model="settings.general.maintenanceMode">
                        <span class="slider"></span>
                    </label>
                    <span class="text-sm text-gray-600">Enable maintenance mode to disable public access</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Settings -->
    <div x-show="activeTab === 'email'" class="tab-content">
        <div class="settings-card">
            <div class="settings-header">
                <h2 class="text-xl font-semibold text-gray-900">Email Configuration</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label">Mail Driver</label>
                    <select x-model="settings.email.driver" class="form-input form-select">
                        <option value="smtp">SMTP</option>
                        <option value="mail">PHP Mail</option>
                        <option value="sendmail">Sendmail</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Mail Host</label>
                    <input type="text" x-model="settings.email.host" class="form-input" placeholder="smtp.gmail.com">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Mail Port</label>
                    <input type="number" x-model="settings.email.port" class="form-input" placeholder="587">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Mail Username</label>
                    <input type="text" x-model="settings.email.username" class="form-input" placeholder="your-email@gmail.com">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Mail Password</label>
                    <input type="password" x-model="settings.email.password" class="form-input" placeholder="••••••••">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Encryption</label>
                    <select x-model="settings.email.encryption" class="form-input form-select">
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                        <option value="">None</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label">From Address</label>
                    <input type="email" x-model="settings.email.fromAddress" class="form-input" placeholder="noreply@flyoverbd.com">
                </div>
                
                <div class="form-group">
                    <label class="form-label">From Name</label>
                    <input type="text" x-model="settings.email.fromName" class="form-input" placeholder="FlyoverBD">
                </div>
            </div>
            
            <div class="form-group">
                <button @click="testEmailSettings()" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Send Test Email
                </button>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div x-show="activeTab === 'security'" class="tab-content">
        <div class="settings-card">
            <div class="settings-header">
                <h2 class="text-xl font-semibold text-gray-900">Security Settings</h2>
            </div>
            
            <div class="space-y-6">
                <div class="form-group">
                    <label class="form-label">Force HTTPS</label>
                    <div class="flex items-center gap-3">
                        <label class="switch">
                            <input type="checkbox" x-model="settings.security.forceHttps">
                            <span class="slider"></span>
                        </label>
                        <span class="text-sm text-gray-600">Redirect all HTTP requests to HTTPS</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Session Lifetime (minutes)</label>
                    <input type="number" x-model="settings.security.sessionLifetime" class="form-input" placeholder="120">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password Requirements</label>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <label class="switch">
                                <input type="checkbox" x-model="settings.security.passwordMinLength">
                                <span class="slider"></span>
                            </label>
                            <span class="text-sm text-gray-600">Minimum 8 characters</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="switch">
                                <input type="checkbox" x-model="settings.security.passwordUppercase">
                                <span class="slider"></span>
                            </label>
                            <span class="text-sm text-gray-600">Require uppercase letters</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="switch">
                                <input type="checkbox" x-model="settings.security.passwordNumbers">
                                <span class="slider"></span>
                            </label>
                            <span class="text-sm text-gray-600">Require numbers</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="switch">
                                <input type="checkbox" x-model="settings.security.passwordSymbols">
                                <span class="slider"></span>
                            </label>
                            <span class="text-sm text-gray-600">Require special characters</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Two-Factor Authentication</label>
                    <div class="flex items-center gap-3">
                        <label class="switch">
                            <input type="checkbox" x-model="settings.security.twoFactorAuth">
                            <span class="slider"></span>
                        </label>
                        <span class="text-sm text-gray-600">Enable 2FA for admin accounts</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Settings -->
    <div x-show="activeTab === 'payment'" class="tab-content">
        <div class="settings-card">
            <div class="settings-header">
                <h2 class="text-xl font-semibold text-gray-900">Payment Gateway Settings</h2>
            </div>
            
            <div class="space-y-6">
                <div class="form-group">
                    <label class="form-label">Default Currency</label>
                    <select x-model="settings.payment.currency" class="form-input form-select">
                        <option value="BDT">Bangladeshi Taka (BDT)</option>
                        <option value="USD">US Dollar (USD)</option>
                        <option value="EUR">Euro (EUR)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">SSL Commerce</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" x-model="settings.payment.sslStoreId" class="form-input" placeholder="Store ID">
                        <input type="text" x-model="settings.payment.sslStorePassword" class="form-input" placeholder="Store Password">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Stripe</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" x-model="settings.payment.stripeKey" class="form-input" placeholder="Publishable Key">
                        <input type="text" x-model="settings.payment.stripeSecret" class="form-input" placeholder="Secret Key">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Settings -->
    <div x-show="activeTab === 'social'" class="tab-content">
        <div class="settings-card">
            <div class="settings-header">
                <h2 class="text-xl font-semibold text-gray-900">Social Media Settings</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label">Facebook URL</label>
                    <input type="url" x-model="settings.social.facebook" class="form-input" placeholder="https://facebook.com/flyoverbd">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Twitter URL</label>
                    <input type="url" x-model="settings.social.twitter" class="form-input" placeholder="https://twitter.com/flyoverbd">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Instagram URL</label>
                    <input type="url" x-model="settings.social.instagram" class="form-input" placeholder="https://instagram.com/flyoverbd">
                </div>
                
                <div class="form-group">
                    <label class="form-label">LinkedIn URL</label>
                    <input type="url" x-model="settings.social.linkedin" class="form-input" placeholder="https://linkedin.com/company/flyoverbd">
                </div>
                
                <div class="form-group">
                    <label class="form-label">YouTube URL</label>
                    <input type="url" x-model="settings.social.youtube" class="form-input" placeholder="https://youtube.com/flyoverbd">
                </div>
                
                <div class="form-group">
                    <label class="form-label">WhatsApp Number</label>
                    <input type="tel" x-model="settings.social.whatsapp" class="form-input" placeholder="+8801234567890">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function settingsManager() {
    return {
        activeTab: 'general',
        notification: {
            show: false,
            type: 'success',
            message: ''
        },
        settings: {
            general: {
                appName: 'FlyoverBD',
                appUrl: 'https://flyoverbd.com',
                language: 'en',
                timezone: 'Asia/Dhaka',
                contactEmail: 'info@flyoverbd.com',
                contactPhone: '+8801234567890',
                siteDescription: 'Your trusted travel partner for amazing journeys',
                maintenanceMode: false
            },
            email: {
                driver: 'smtp',
                host: 'smtp.gmail.com',
                port: '587',
                username: '',
                password: '',
                encryption: 'tls',
                fromAddress: 'noreply@flyoverbd.com',
                fromName: 'FlyoverBD'
            },
            security: {
                forceHttps: true,
                sessionLifetime: 120,
                passwordMinLength: true,
                passwordUppercase: true,
                passwordNumbers: true,
                passwordSymbols: false,
                twoFactorAuth: false
            },
            payment: {
                currency: 'BDT',
                sslStoreId: '',
                sslStorePassword: '',
                stripeKey: '',
                stripeSecret: ''
            },
            social: {
                facebook: '',
                twitter: '',
                instagram: '',
                linkedin: '',
                youtube: '',
                whatsapp: ''
            }
        },
        
        init() {
            this.loadSettings();
        },
        
        loadSettings() {
            // Load settings from localStorage or API
            const saved = localStorage.getItem('adminSettings');
            if (saved) {
                this.settings = JSON.parse(saved);
            }
        },
        
        async saveAllSettings() {
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 500));
                
                localStorage.setItem('adminSettings', JSON.stringify(this.settings));
                this.showNotification('Settings saved successfully!', 'success');
            } catch (error) {
                this.showNotification('Error saving settings!', 'error');
            }
        },
        
        async resetSettings() {
            if (confirm('Are you sure you want to reset all settings to default values?')) {
                this.settings = {
                    general: {
                        appName: 'FlyoverBD',
                        appUrl: 'https://flyoverbd.com',
                        language: 'en',
                        timezone: 'Asia/Dhaka',
                        contactEmail: 'info@flyoverbd.com',
                        contactPhone: '+8801234567890',
                        siteDescription: 'Your trusted travel partner for amazing journeys',
                        maintenanceMode: false
                    },
                    email: {
                        driver: 'smtp',
                        host: 'smtp.gmail.com',
                        port: '587',
                        username: '',
                        password: '',
                        encryption: 'tls',
                        fromAddress: 'noreply@flyoverbd.com',
                        fromName: 'FlyoverBD'
                    },
                    security: {
                        forceHttps: true,
                        sessionLifetime: 120,
                        passwordMinLength: true,
                        passwordUppercase: true,
                        passwordNumbers: true,
                        passwordSymbols: false,
                        twoFactorAuth: false
                    },
                    payment: {
                        currency: 'BDT',
                        sslStoreId: '',
                        sslStorePassword: '',
                        stripeKey: '',
                        stripeSecret: ''
                    },
                    social: {
                        facebook: '',
                        twitter: '',
                        instagram: '',
                        linkedin: '',
                        youtube: '',
                        whatsapp: ''
                    }
                };
                
                localStorage.removeItem('adminSettings');
                this.showNotification('Settings reset to defaults!', 'success');
            }
        },
        
        async testEmailSettings() {
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                this.showNotification('Test email sent successfully!', 'success');
            } catch (error) {
                this.showNotification('Failed to send test email!', 'error');
            }
        },
        
        showNotification(message, type = 'success') {
            this.notification = {
                show: true,
                type: type,
                message: message
            };
            
            setTimeout(() => {
                this.notification.show = false;
            }, 3000);
        }
    }
}
</script>
@endpush
</x-admin-layout>
