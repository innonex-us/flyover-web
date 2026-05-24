<x-admin-layout pageTitle="Create Short Link">

    <div class="mb-8">
        <a href="{{ route('admin.short-links.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Short Links
        </a>
        <h2 class="text-3xl font-bold text-gray-800">Create Short Link</h2>
    </div>

    <div class="max-w-2xl" x-data="{
        urlField: '',
        selectedPackage: '',
        selectedVisa: '',
        selectedPost: '',
        packages: {{ json_encode($packages->map(fn($p) => ['id' => $p->id, 'title' => $p->title, 'url' => route('packages.show', $p->slug)])) }},
        visas: {{ json_encode($visas->map(fn($v) => ['id' => $v->id, 'title' => $v->country . ' (' . $v->type . ')', 'url' => route('visas.show', $v->slug)])) }},
        posts: {{ json_encode($posts->map(fn($p) => ['id' => $p->id, 'title' => $p->title, 'url' => route('blog.show', $p->slug)])) }},
        usePackageUrl() {
            const pkg = this.packages.find(p => p.id == this.selectedPackage);
            if (pkg) this.urlField = pkg.url;
        },
        useVisaUrl() {
            const visa = this.visas.find(v => v.id == this.selectedVisa);
            if (visa) this.urlField = visa.url;
        },
        usePostUrl() {
            const post = this.posts.find(p => p.id == this.selectedPost);
            if (post) this.urlField = post.url;
        }
    }">

        {{-- Quick-pick Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-widest">Quick-pick a URL</h3>
            <div class="space-y-4">
                {{-- Packages --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Tour Package</label>
                    <div class="flex gap-2">
                        <select x-model="selectedPackage" class="flex-1 py-2 text-sm border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                            <option value="">Select a package...</option>
                            <template x-for="pkg in packages" :key="pkg.id">
                                <option :value="pkg.id" x-text="pkg.title"></option>
                            </template>
                        </select>
                        <button type="button" @click="usePackageUrl()" class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex-shrink-0">
                            Use URL
                        </button>
                    </div>
                </div>

                {{-- Visas --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Visa Service</label>
                    <div class="flex gap-2">
                        <select x-model="selectedVisa" class="flex-1 py-2 text-sm border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                            <option value="">Select a visa...</option>
                            <template x-for="visa in visas" :key="visa.id">
                                <option :value="visa.id" x-text="visa.title"></option>
                            </template>
                        </select>
                        <button type="button" @click="useVisaUrl()" class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex-shrink-0">
                            Use URL
                        </button>
                    </div>
                </div>

                {{-- Blog Posts --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Blog Post</label>
                    <div class="flex gap-2">
                        <select x-model="selectedPost" class="flex-1 py-2 text-sm border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                            <option value="">Select a post...</option>
                            <template x-for="post in posts" :key="post.id">
                                <option :value="post.id" x-text="post.title"></option>
                            </template>
                        </select>
                        <button type="button" @click="usePostUrl()" class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex-shrink-0">
                            Use URL
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.short-links.store') }}" method="POST">
            @csrf
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-widest border-b pb-2">Link Details</h3>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Destination URL <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="url"
                        name="url"
                        x-model="urlField"
                        value="{{ old('url') }}"
                        required
                        placeholder="https://example.com/your-page"
                        class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200"
                    >
                    @error('url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Label <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input
                        type="text"
                        name="label"
                        value="{{ old('label') }}"
                        placeholder="e.g. Cox's Bazar Summer Promo"
                        class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200"
                    >
                    @error('label') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('admin.short-links.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition">
                        Cancel
                    </a>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition">
                        Create Short Link
                    </button>
                </div>
            </div>
        </form>
    </div>

</x-admin-layout>
