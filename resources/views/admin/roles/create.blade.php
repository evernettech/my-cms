<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('admin.roles.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="name">
                            {{ __('Role Name') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. Admin, Editor"
                            class="mt-2 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- Permissions -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="permissions">
                            {{ __('Permissions') }} <span class="text-red-600">*</span>
                        </label>
                        <select id="permissions" name="permissions[]" multiple class="tom-select w-full mt-2">
                            @foreach($permissions as $permission)
                                <option value="{{ $permission->name }}"
                                    @if(collect(old('permissions'))->contains($permission->id)) selected @endif>
                                    {{ $permission->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <x-primary-button>{{ __('Create New Role') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#permissions', {
                plugins: ['remove_button'],
                persist: false,
                create: false,
                maxItems: null,
                placeholder: 'Select permissions...',
                allowEmptyOption: false,
            });
        });
    </script>

</x-app-layout>