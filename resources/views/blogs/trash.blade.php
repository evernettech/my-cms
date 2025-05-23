<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trash') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p>{{ __('Manage your trashed blogs here.') }}</p>
                </div>
            </div>
            {{-- trash blog list --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <livewire:trash-blog-table />
            </div>
        </div>
    </div>
</x-app-layout>