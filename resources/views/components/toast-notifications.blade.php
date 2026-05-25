<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('toastManager', () => ({
            toasts: [],
            addToast(type, message, description = '') {
                const id = Date.now() + Math.random().toString(36).substring(2, 9);
                this.toasts.push({ id, type, message, description, show: true });
                
                setTimeout(() => {
                    this.removeToast(id);
                }, 5000);
            },
            removeToast(id) {
                const index = this.toasts.findIndex(t => t.id === id);
                if (index > -1) {
                    this.toasts[index].show = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300);
                }
            },
            init() {
                @if(session()->has('success'))
                    setTimeout(() => this.addToast('success', @json(session('success'))), 100);
                @endif
                @if(session()->has('error'))
                    setTimeout(() => this.addToast('error', @json(session('error'))), 100);
                @endif
                @if(session()->has('warning'))
                    setTimeout(() => this.addToast('warning', @json(session('warning'))), 100);
                @endif
                @if(session()->has('info'))
                    setTimeout(() => this.addToast('info', @json(session('info'))), 100);
                @endif

                @if(isset($errors) && $errors->any())
                    @foreach($errors->all() as $error)
                        setTimeout(() => this.addToast('error', @json($error)), 100);
                    @endforeach
                @endif

                window.addEventListener('notify', (e) => {
                    this.addToast(e.detail.type || 'info', e.detail.message, e.detail.description || '');
                });
            }
        }));
    });
</script>

<div
    x-data="toastManager"
    class="fixed bottom-[88px] md:bottom-10 right-4 left-4 md:left-auto z-[9999] flex flex-col items-end gap-3 pointer-events-none"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
            x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto w-full md:max-w-sm overflow-hidden rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 flex items-start p-4"
            :class="{
                'bg-white border-l-4 border-green-500': toast.type === 'success',
                'bg-white border-l-4 border-red-500': toast.type === 'error',
                'bg-white border-l-4 border-yellow-500': toast.type === 'warning',
                'bg-white border-l-4 border-blue-500': toast.type === 'info',
            }"
        >
            <div class="flex-shrink-0 mt-0.5">
                <!-- Success Icon -->
                <svg x-show="toast.type === 'success'" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <!-- Error Icon -->
                <svg x-show="toast.type === 'error'" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <!-- Warning Icon -->
                <svg x-show="toast.type === 'warning'" class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <!-- Info Icon -->
                <svg x-show="toast.type === 'info'" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
            </div>
            <div class="ml-3 min-w-0 flex-1">
                <p class="text-sm font-bold text-gray-900" x-text="toast.message"></p>
                <template x-if="toast.description">
                    <p class="mt-1 text-xs text-gray-500" x-text="toast.description"></p>
                </template>
            </div>
            <div class="ml-4 flex flex-shrink-0">
                <button @click="removeToast(toast.id)" type="button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none transition">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </button>
            </div>
        </div>
    </template>
</div>
