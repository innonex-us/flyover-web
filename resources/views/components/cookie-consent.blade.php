@php
    $consent = request()->cookie('tracking_consent');
    $showConsent = !$consent;
@endphp

@if($showConsent)
<div id="cookie-consent" class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 z-50 shadow-lg">
    <div class="container mx-auto max-w-6xl">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex-1">
                <h3 class="text-lg font-semibold mb-2">🍪 Privacy & Cookies</h3>
                <p class="text-sm text-gray-300 mb-3">
                    We use cookies and tracking technologies to enhance your experience, analyze site traffic, and personalize content. 
                    Your privacy is important to us, and you can control what data you share with us.
                </p>
                <div class="flex flex-wrap gap-2 mb-3">
                    <button onclick="showCookieDetails()" class="text-xs text-blue-300 hover:text-blue-200 underline">
                        Learn more about cookies
                    </button>
                    <a href="{{ route('privacy') }}" class="text-xs text-blue-300 hover:text-blue-200 underline">
                        Privacy Policy
                    </a>
                </div>
                
                @if(request()->cookie('show_detailed_consent') !== 'hidden')
                <div id="cookie-details" class="hidden bg-gray-800 rounded p-3 mb-3 text-sm">
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="essential-cookies" checked disabled class="rounded">
                            <span>Essential Cookies (Required)</span>
                        </label>
                        <p class="text-xs text-gray-400 ml-6">Required for the site to function properly</p>
                        
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="analytics-cookies" checked class="rounded">
                            <span>Analytics Cookies</span>
                        </label>
                        <p class="text-xs text-gray-400 ml-6">Help us understand how visitors interact with our site</p>
                        
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="marketing-cookies" class="rounded">
                            <span>Marketing Cookies</span>
                        </label>
                        <p class="text-xs text-gray-400 ml-6">Used to personalize ads and marketing content</p>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2">
                <button onclick="acceptAllCookies()" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded font-medium text-sm transition-colors">
                    Accept All
                </button>
                <button onclick="acceptEssentialOnly()" 
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded font-medium text-sm transition-colors">
                    Essential Only
                </button>
                <button onclick="customizeCookies()" 
                        class="px-4 py-2 border border-gray-600 hover:bg-gray-800 rounded font-medium text-sm transition-colors">
                    Customize
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showCookieDetails() {
    const details = document.getElementById('cookie-details');
    details.classList.toggle('hidden');
}

function acceptAllCookies() {
    setCookieConsent('full');
    hideCookieConsent();
    enableAnalytics();
}

function acceptEssentialOnly() {
    setCookieConsent('essential');
    hideCookieConsent();
    disableAnalytics();
}

function customizeCookies() {
    const details = document.getElementById('cookie-details');
    details.classList.remove('hidden');
    
    // Change buttons
    const consentDiv = document.getElementById('cookie-consent');
    const buttonContainer = consentDiv.querySelector('.flex.flex-col.sm\\:flex-row.gap-2');
    
    buttonContainer.innerHTML = `
        <button onclick="saveCustomPreferences()" 
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded font-medium text-sm transition-colors">
            Save Preferences
        </button>
        <button onclick="hideCookieConsent()" 
                class="px-4 py-2 border border-gray-600 hover:bg-gray-800 rounded font-medium text-sm transition-colors">
            Cancel
        </button>
    `;
}

function saveCustomPreferences() {
    const analyticsCookies = document.getElementById('analytics-cookies').checked;
    const marketingCookies = document.getElementById('marketing-cookies').checked;
    
    let consentLevel = 'essential';
    if (analyticsCookies && marketingCookies) {
        consentLevel = 'full';
    } else if (analyticsCookies) {
        consentLevel = 'analytics';
    }
    
    setCookieConsent(consentLevel);
    hideCookieConsent();
    
    if (analyticsCookies) {
        enableAnalytics();
    } else {
        disableAnalytics();
    }
}

function setCookieConsent(level) {
    // Set consent cookie for 1 year
    document.cookie = `tracking_consent=${level === 'full' ? 'accepted' : 'rejected'}; path=/; max-age=${365 * 24 * 60 * 60}; SameSite=Lax`;
    
    // Store detailed consent preferences
    localStorage.setItem('cookie_consent_level', level);
    localStorage.setItem('cookie_consent_date', new Date().toISOString());
    
    // Send consent to server
    fetch('/api/analytics/consent', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify({
            visitor_id: getCookie('visitor_id'),
            consent_level: level
        })
    });
}

function hideCookieConsent() {
    const consentDiv = document.getElementById('cookie-consent');
    consentDiv.style.display = 'none';
    document.cookie = 'show_detailed_consent=hidden; path=/; max-age=${30 * 24 * 60 * 60}; SameSite=Lax';
}

function enableAnalytics() {
    if (typeof handleAnalyticsConsent === 'function') {
        handleAnalyticsConsent(true);
    }
}

function disableAnalytics() {
    if (typeof handleAnalyticsConsent === 'function') {
        handleAnalyticsConsent(false);
    }
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

// Auto-hide after 30 seconds if no action
setTimeout(() => {
    const consentDiv = document.getElementById('cookie-consent');
    if (consentDiv && consentDiv.style.display !== 'none') {
        consentDiv.style.opacity = '0.7';
    }
}, 30000);
</script>
@endif
