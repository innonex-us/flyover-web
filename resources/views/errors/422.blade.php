@include('errors.layout', [
    'code'      => 422,
    'title'     => 'Unprocessable Content',
    'message'   => 'The data you submitted was understood but contains validation errors. Please review your input and correct any issues before resubmitting.',
    'iconColor' => 'icon-orange',
    'icon'      => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
    'infoBox'   => [
        'type'    => 'orange',
        'heading' => 'Validation Failed',
        'body'    => 'One or more fields in your submission did not pass validation. Please go back, correct the highlighted errors, and try submitting the form again.',
    ],
    'actions'   => '
        <button onclick="history.back()" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Fix & Go Back
        </button>
        <a href="' . url('/') . '" class="btn btn-ghost">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Return to Home
        </a>
    ',
])
