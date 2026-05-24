class VisitorAnalytics {
    constructor() {
        this.visitorId = null;
        this.sessionId = null;
        this.currentPageView = null;
        this.scrollDepth = 0;
        this.maxScrollDepth = 0;
        this.startTime = Date.now();
        this.lastActivity = Date.now();
        this.events = [];
        this.performanceMetrics = {};
        
        this.init();
    }

    init() {
        // Get visitor and session IDs from cookies
        this.visitorId = this.getCookie('visitor_id');
        this.sessionId = this.getCookie('session_id');
        
        if (!this.visitorId || !this.sessionId) {
            console.warn('Analytics: Missing visitor or session ID');
            return;
        }

        // Start tracking
        this.trackPageView();
        this.setupEventListeners();
        this.trackPerformanceMetrics();
        this.updateVisitorInfo();
        
        // Start heartbeat
        this.startHeartbeat();
        
        // Track page unload
        window.addEventListener('beforeunload', () => {
            this.trackPageUnload();
        });
        
        // Track visibility changes
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.trackPageFocus(false);
            } else {
                this.trackPageFocus(true);
            }
        });
    }

    trackPageView() {
        const pageData = {
            visitor_id: this.visitorId,
            session_id: this.sessionId,
            url: window.location.href,
            title: document.title,
            path: window.location.pathname,
            query_params: window.location.search.substring(1),
            hash: window.location.hash,
            timestamp: new Date().toISOString(),
            screen_resolution: {
                width: screen.width,
                height: screen.height
            },
            viewport_size: {
                width: window.innerWidth,
                height: window.innerHeight
            },
            cookies_enabled: navigator.cookieEnabled,
            javascript_enabled: true,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            language: navigator.language,
            referrer: document.referrer
        };

        this.sendApiRequest('/api/analytics/page-view', pageData)
            .then(response => {
                if (response.success) {
                    this.currentPageView = response.page_view_id;
                }
            })
            .catch(error => {
                console.error('Analytics: Failed to track page view', error);
            });
    }

    trackEvent(eventName, data = {}) {
        const eventData = {
            visitor_id: this.visitorId,
            session_id: this.sessionId,
            page_view_id: this.currentPageView,
            event: eventName,
            data: data,
            timestamp: new Date().toISOString()
        };

        this.events.push(eventData);
        this.lastActivity = Date.now();

        this.sendApiRequest('/api/analytics/event', eventData)
            .catch(error => {
                console.error('Analytics: Failed to track event', error);
            });
    }

    trackInteraction(type, element, data = {}) {
        const interactionData = {
            type: type,
            element: {
                tag: element.tagName,
                id: element.id,
                class: element.className,
                text: element.textContent?.substring(0, 100),
                href: element.href
            },
            data: data
        };

        this.trackEvent('interaction', interactionData);
        
        // Update session interaction count
        this.updateSessionActivity();
    }

    trackScrollDepth() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const documentHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrollPercent = Math.round((scrollTop / documentHeight) * 100);
        
        this.scrollDepth = scrollPercent;
        
        if (scrollPercent > this.maxScrollDepth) {
            this.maxScrollDepth = scrollPercent;
            
            // Track milestone scroll depths
            const milestones = [25, 50, 75, 90];
            milestones.forEach(milestone => {
                if (scrollPercent >= milestone && !this.events.find(e => e.event === 'scroll_milestone' && e.data.depth === milestone)) {
                    this.trackEvent('scroll_milestone', { depth: milestone });
                }
            });
        }
    }

    trackPerformanceMetrics() {
        if ('performance' in window) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const navigation = performance.getEntriesByType('navigation')[0];
                    const paint = performance.getEntriesByType('paint');
                    
                    this.performanceMetrics = {
                        // Page load times
                        dom_content_loaded: navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart,
                        load_complete: navigation.loadEventEnd - navigation.loadEventStart,
                        time_to_first_byte: navigation.responseStart - navigation.requestStart,
                        
                        // Paint times
                        first_paint: paint.find(p => p.name === 'first-paint')?.startTime,
                        first_contentful_paint: paint.find(p => p.name === 'first-contentful-paint')?.startTime,
                        
                        // Network times
                        dns_lookup: navigation.domainLookupEnd - navigation.domainLookupStart,
                        tcp_connect: navigation.connectEnd - navigation.connectStart,
                        ssl_connect: navigation.secureConnectionStart > 0 ? navigation.connectEnd - navigation.secureConnectionStart : null,
                        
                        // Memory usage (if available)
                        memory_used: performance.memory ? {
                            used: performance.memory.usedJSHeapSize,
                            total: performance.memory.totalJSHeapSize,
                            limit: performance.memory.jsHeapSizeLimit
                        } : null
                    };

                    this.trackEvent('performance_metrics', this.performanceMetrics);
                }, 0);
            });
        }
    }

    updateVisitorInfo() {
        const visitorData = {
            screen_resolution: {
                width: screen.width,
                height: screen.height
            },
            viewport_size: {
                width: window.innerWidth,
                height: window.innerHeight
            },
            cookies_enabled: navigator.cookieEnabled,
            javascript_enabled: true,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            language: navigator.language,
            online: navigator.onLine,
            connection: this.getConnectionInfo()
        };

        this.sendApiRequest('/api/analytics/visitor-update', visitorData)
            .catch(error => {
                console.error('Analytics: Failed to update visitor info', error);
            });
    }

    getConnectionInfo() {
        if ('connection' in navigator) {
            const conn = navigator.connection;
            return {
                effective_type: conn.effectiveType,
                downlink: conn.downlink,
                rtt: conn.rtt,
                save_data: conn.saveData
            };
        }
        return null;
    }

    updateSessionActivity() {
        this.sendApiRequest('/api/analytics/session-activity', {
            session_id: this.sessionId,
            timestamp: new Date().toISOString()
        }).catch(error => {
            console.error('Analytics: Failed to update session activity', error);
        });
    }

    trackPageUnload() {
        const timeOnPage = Date.now() - this.startTime;
        
        this.trackEvent('page_unload', {
            time_on_page: timeOnPage,
            max_scroll_depth: this.maxScrollDepth,
            total_events: this.events.length
        });

        // Send final page view update
        this.sendApiRequest('/api/analytics/page-view-update', {
            page_view_id: this.currentPageView,
            time_on_page: timeOnPage,
            max_scroll_depth: this.maxScrollDepth,
            is_exit_page: true
        }, true); // Use sendBeacon for unload
    }

    trackPageFocus(isFocused) {
        this.trackEvent('page_focus_change', { focused: isFocused });
    }

    setupEventListeners() {
        // Click tracking
        document.addEventListener('click', (e) => {
            this.trackInteraction('click', e.target, {
                x: e.clientX,
                y: e.clientY
            });
        });

        // Scroll tracking
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            this.trackScrollDepth();
            
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.trackEvent('scroll', {
                    depth: this.scrollDepth,
                    max_depth: this.maxScrollDepth
                });
            }, 100);
        });

        // Form interactions
        document.addEventListener('submit', (e) => {
            this.trackInteraction('form_submit', e.target);
        });

        document.addEventListener('focus', (e) => {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
                this.trackInteraction('form_focus', e.target);
            }
        }, true);

        // Error tracking
        window.addEventListener('error', (e) => {
            this.trackEvent('javascript_error', {
                message: e.message,
                filename: e.filename,
                lineno: e.lineno,
                colno: e.colno,
                stack: e.error?.stack
            });
        });

        // Network errors
        window.addEventListener('unhandledrejection', (e) => {
            this.trackEvent('promise_rejection', {
                reason: e.reason
            });
        });
    }

    startHeartbeat() {
        setInterval(() => {
            const timeSinceLastActivity = Date.now() - this.lastActivity;
            
            // Send heartbeat every 30 seconds if there was activity
            if (timeSinceLastActivity < 30000) {
                this.trackEvent('heartbeat', {
                    time_on_page: Date.now() - this.startTime,
                    scroll_depth: this.scrollDepth
                });
            }
        }, 30000);
    }

    sendApiRequest(endpoint, data, useBeacon = false) {
        const url = window.location.origin + endpoint;
        
        if (useBeacon && 'sendBeacon' in navigator) {
            navigator.sendBeacon(url, JSON.stringify(data));
            return Promise.resolve({ success: true });
        }
        
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(data)
        }).then(response => response.json());
    }

    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    // Public methods for manual tracking
    trackCustomEvent(eventName, data) {
        this.trackEvent(eventName, data);
    }

    trackConversion(type, value = null, currency = null) {
        this.trackEvent('conversion', {
            type: type,
            value: value,
            currency: currency
        });
    }

    trackFormSubmission(formName, formData = {}) {
        this.trackEvent('form_submission', {
            form_name: formName,
            form_data: formData
        });
    }

    trackDownload(url, filename = null) {
        this.trackEvent('download', {
            url: url,
            filename: filename
        });
    }

    trackVideoPlay(videoTitle, videoUrl) {
        this.trackEvent('video_play', {
            title: videoTitle,
            url: videoUrl
        });
    }

    trackSearch(query, resultsCount = null) {
        this.trackEvent('search', {
            query: query,
            results_count: resultsCount
        });
    }
}

// Initialize analytics when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize if tracking consent is given
    const consent = document.cookie.split('; ').find(row => row.startsWith('tracking_consent='));
    
    if (consent && consent.split('=')[1] === 'accepted') {
        window.analytics = new VisitorAnalytics();
    }
});

// Global function to handle consent changes
window.handleAnalyticsConsent = function(accepted) {
    if (accepted) {
        if (!window.analytics) {
            window.analytics = new VisitorAnalytics();
        }
    } else {
        // Disable analytics
        if (window.analytics) {
            window.analytics = null;
        }
    }
};
