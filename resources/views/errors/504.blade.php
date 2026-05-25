@include('errors.layout', [
    'code'      => 504,
    'title'     => 'Gateway Timeout',
    'message'   => 'The upstream server didn\'t respond in time. This is usually temporary - please wait a moment and try your request again.',
    'iconColor' => 'icon-orange',
    'icon'      => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'infoBox'   => [
        'type'    => 'orange',
        'heading' => 'Upstream Server Not Responding',
        'body'    => 'A gateway server did not receive a timely response from an upstream server. This is often a brief network hiccup. Please try again in a few seconds.',
    ],
    'actions'   => '
        <button onclick="window.location.reload()" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Try Again
        </button>
        <a href="' . url('/') . '" class="btn btn-ghost">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Return to Home
        </a>
    ',
])
