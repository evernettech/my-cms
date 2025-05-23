<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p>{{ __('Manage your roles here.') }}</p>
                    @include('admin.roles.create-role-form')
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <livewire:role-table />
                </div>
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