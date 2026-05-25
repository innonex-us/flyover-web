/**
 * FlyoverBD Analytics Client
 * Tracks page views, events, and visitor data
 */
(function () {
    'use strict';

    // Don't track if Do Not Track is enabled
    if (navigator.doNotTrack === '1' || navigator.doNotTrack === 'yes') {
        return;
    }

    var ANALYTICS_API = '/api/analytics';
    var pageLoadTime = Date.now();
    var maxScroll = 0;
    var pageTitle = document.title;
    var pageUrl = window.location.href;
    var pagePath = window.location.pathname;
    var queryString = window.location.search.replace(/^\?/, '') || null;

    // Track scroll depth
    window.addEventListener('scroll', function () {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        var docHeight = document.documentElement.scrollHeight - window.innerHeight;
        var scrollPercent = docHeight > 0 ? Math.round((scrollTop / docHeight) * 100) : 0;
        if (scrollPercent > maxScroll) {
            maxScroll = scrollPercent;
        }
    });

    // Helper: get cookie
    function getCookie(name) {
        var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? decodeURIComponent(match[2]) : null;
    }

    // Helper: get CSRF token
    function getCsrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // Track page view - server already tracks initial visit via middleware.
    // This sends supplementary client-side data (scroll depth, time on page)
    // once cookies from the server response are available.
    function trackPageView() {
        var visitorId = getCookie('visitor_id');
        var sessionId = getCookie('session_id');

        // On first visit, cookies won't be available yet (set in response headers).
        // Retry after a brief delay to let the browser process the cookies.
        if (!visitorId || !sessionId) {
            setTimeout(function() {
                visitorId = getCookie('visitor_id');
                sessionId = getCookie('session_id');
                if (visitorId && sessionId) {
                    sendPageView(visitorId, sessionId);
                }
            }, 500);
            return;
        }

        sendPageView(visitorId, sessionId);
    }

    function sendPageView(visitorId, sessionId) {
        var payload = {
            visitor_id: parseInt(visitorId, 10),
            session_id: parseInt(sessionId, 10),
            url: pageUrl,
            title: pageTitle,
            path: pagePath,
            referrer: document.referrer || '',
            screen_width: window.screen.width,
            screen_height: window.screen.height,
            viewport_width: window.innerWidth,
            viewport_height: window.innerHeight,
            timestamp: new Date().toISOString()
        };

        if (queryString) payload.query_params = queryString;

        fetch(ANALYTICS_API + '/page-view', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload),
            keepalive: true
        }).then(function (response) {
            if (response.ok) {
                return response.json();
            }
        }).then(function (data) {
            if (data && data.success) {
                // Tracked successfully
            }
        }).catch(function () {
            // Silently fail - analytics should not break the page
        });
    }

    // Track events
    window.trackEvent = function (eventName, eventData) {
        var visitorId = getCookie('visitor_id');
        var sessionId = getCookie('session_id');

        var payload = {
            event: eventName,
            data: eventData || {},
            url: pageUrl
        };

        if (visitorId) payload.visitor_id = parseInt(visitorId, 10);
        if (sessionId) payload.session_id = parseInt(sessionId, 10);

        fetch(ANALYTICS_API + '/event', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload),
            keepalive: true
        }).catch(function () {
            // Silently fail
        });
    };

    // Update page view on exit with scroll depth and time on page
    window.addEventListener('beforeunload', function () {
        var visitorId = getCookie('visitor_id');
        var sessionId = getCookie('session_id');
        if (!visitorId || !sessionId) return;

        var timeOnPage = Math.round((Date.now() - pageLoadTime) / 1000);
        var payload = JSON.stringify({
            time_on_page: timeOnPage,
            max_scroll_depth: maxScroll,
            is_exit_page: true
        });

        // Use sendBeacon for reliable last hit
        if (navigator.sendBeacon) {
            var blob = new Blob([payload], { type: 'application/json' });
            navigator.sendBeacon(ANALYTICS_API + '/page-view-update', blob);
        } else {
            fetch(ANALYTICS_API + '/page-view-update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: payload,
                keepalive: true
            }).catch(function () {});
        }
    });

    // Track consent
    window.trackConsent = function (level) {
        var visitorId = getCookie('visitor_id');
        
        fetch(ANALYTICS_API + '/consent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                consent_level: level,
                visitor_id: visitorId ? parseInt(visitorId, 10) : null
            })
        }).catch(function () {});
    };

    // Initialize tracking after page load (cookies from server response will be available)
    if (document.readyState === 'complete') {
        trackPageView();
    } else {
        window.addEventListener('load', trackPageView);
    }

})();