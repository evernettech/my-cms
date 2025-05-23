<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blogs') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- create blog form --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('blogs.blog_components.create-blog-form')
                </div>
            </div>
            {{-- Blog list table --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <livewire:blog-table />
            </div>
        </div>
    </div>

    {{-- Flash message for success --}}
    @if (session('success'))
        <div id="flash-message"
            class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none">
            <div
                class="bg-green-500 text-black px-6 py-4 rounded shadow-lg transition-opacity duration-500"
                style="opacity: 1;"
            >
                {{ session('success') }}
            </div>
        </div>

        <script>
            setTimeout(() => {
                const msgWrapper = document.getElementById('flash-message');
                if (msgWrapper) {
                    msgWrapper.firstElementChild.style.opacity = '0';
                    setTimeout(() => msgWrapper.remove(), 500); // wait for fade-out
                }
            }, 2000); // 2 seconds display
        </script>
    @endif
</x-app-layout>
