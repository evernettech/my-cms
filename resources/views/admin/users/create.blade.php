<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
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

                <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="name">
                            {{ __('Name') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                            value="{{ old('name') }}"
                            class="mt-2 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="email">
                            {{ __('Email') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="email" name="email" id="email"
                            value="{{ old('email') }}"
                            class="mt-2 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="role">
                            {{ __('Role') }} <span class="text-red-600">*</span>
                        </label>
                        <select name="role" id="role"
                            class="mt-2 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="password">
                            {{ __('Password') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="password" name="password" id="password"
                            class="mt-2 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    {{-- password confirm --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="password_confirmation">
                            {{ __('Confirm Password') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="mt-2 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <x-primary-button>{{ __('Create New User') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
</x-app-layout>