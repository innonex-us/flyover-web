@include('errors.layout', [
    'code'      => 400,
    'title'     => 'Bad Request',
    'message'   => 'The server could not understand your request due to invalid syntax or malformed data. Please check your input and try again.',
    'iconColor' => 'icon-orange',
    'icon'      => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'infoBox'   => [
        'type'    => 'orange',
        'heading' => 'Invalid Request',
        'body'    => 'Your request contained invalid or unexpected data. Please verify that all required fields are correctly filled in and that your URL is valid.',
    ],
    'actions'   => '
        <button onclick="history.back()" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Go Back
        </button>
        <a href="' . url('/') . '" class="btn btn-ghost">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Return to Home
        </a>
    ',
])
