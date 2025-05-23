<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Blog') }}
        </h2>
    </x-slot>

    @vite(['resources/js/tinymce-init.js'])

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('blogs.update', $blog->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Title -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="title">
                            {{ __('Blog Title') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="title" id="title"
                            value="{{ old('title', $blog->title) }}" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    {{-- SEO title --}}
                     <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="seo_title">
                           {{ __('SEO Title') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="seo_title" id="seo_title"
                            value="{{ old('seo_title', $blog->seo_title) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    {{-- SEO keywords --}}
                     <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="seo_keywords">
                            {{ __('SEO Keywords') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="seo_keywords" id="seo_keywords"
                            value="{{ old('seo_keywords', $blog->seo_keywords) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    {{-- SEO description --}}
                     <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="seo_description">
                            {{ __('SEO Description') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="seo_description" id="seo_description"
                            value="{{ old('seo_description', $blog->seo_description) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- Content -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="content">
                            {{ __('Content') }} <span class="text-red-600">*</span>
                        </label>
                        <textarea name="content" id="content" rows="6" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('content', $blog->content) }}</textarea>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <x-primary-button>{{ __('Update Blog') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
<style>
  /* Hide TinyMCE promo banner */
  .tox-promotion {
    display: none !important;
  }
</style>
</x-app-layout>
