<x-admin-layout>
    <div class="mb-8">
        <a href="{{ route('profile.edit') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Profile
        </a>
        <h2 class="text-3xl font-bold text-gray-800">Set up Two-Factor Authentication</h2>
        <p class="text-sm text-gray-500 mt-1">Scan the QR code to secure your account.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- QR Code Column -->
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center">
            <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-50">
                <!-- We use a JS library to render the QR code from the URL -->
                 <div id="qrcode"></div>
                 <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
                 <script>
                     new QRCode(document.getElementById("qrcode"), {
                        text: "{{ $qrCodeUrl }}",
                        width: 200,
                        height: 200,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                 </script>
            </div>
            
            <div class="text-center max-w-xs">
                <p class="text-sm font-semibold text-gray-700 mb-2">Can't scan the code?</p>
                <p class="text-xs text-gray-500 break-all bg-gray-50 p-3 rounded border border-gray-200 font-mono">
                    {{ $secret }}
                </p>
            </div>
        </div>

        <!-- Verification Form Column -->
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Verify Setup</h3>
            <p class="text-gray-600 mb-6 text-sm">
                Enter the 6-digit code from your authenticator app to enable Two-Factor Authentication.
            </p>

            <form action="{{ route('two-factor.confirm') }}" method="POST">
                @csrf
                <input type="hidden" name="secret" value="{{ $secret }}">
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Authentication Code</label>
                    <input type="text" name="code" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-center text-2xl tracking-widest font-mono py-3" placeholder="000 000" maxlength="6" required autofocus>
                    @error('code')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-0.5">
                        Verify and Enable
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-admin-layout>
