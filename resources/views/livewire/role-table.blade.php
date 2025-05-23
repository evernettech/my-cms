<div>
    <div class="mb-4 flex justify-between items-center" style="column-gap: 20px;">
        <input 
            type="text" 
            wire:model="search"
            placeholder="{{ __('Search roles...') }}" 

            class="border rounded px-4 py-2 max-w-xs"
        />
        <div class="flex items-center gap-2">
            <button type="button" wire:click="$refresh"
                class="border bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition whitespace-nowrap"> 
                🔍 {{ __('Search') }}
            </button>

            <button type="button" wire:click="resetFilters"
                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition whitespace-nowrap">
                ⟳ {{ __('Reset') }}
            </button>
        </div>
        <select wire:model.live="perPage" class="border rounded px-2 py-1" style="margin-left: auto;">
            @foreach([10, 20, 30, 40, 50] as $size)
                <option value="{{ $size }}">{{ $size }} {{ __('per page') }}</option>
            @endforeach
        </select>
    </div>

    <div x-data="roleEditModal()">
        <table class="min-w-full divide-y divide-gray-200 bg-white text-sm text-gray-700">
            <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-center">#</th>
                    <th class="px-4 py-3 text-center">{{ __('Role Name') }}</th>
                    <th class="px-4 py-3 text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($roles as $index => $role)
                    <tr>
                        <td class="px-6 py-4 text-center">{{ $roles->firstItem() + $index }}</td>
                        <td class="px-6 py-4 text-center">{{ ucfirst($role->name) }}</td>
                        <td class="px-6 py-4 text-center">
                            {{-- Edit and Delete buttons --}}
                            @php
                                $roleData = [
                                    'id' => $role->id,
                                    'name' => $role->name,
                                    'permissions' => $role->permissions->pluck('name')->toArray(),
                                ];
                            @endphp
                            <button 
                                type="button"
                                x-data='{ role: @json($roleData) }'
                                @click="openModal(role)"
                                class="inline-block px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition"
                            >
                                {{ __('Edit') }}
                            </button>
                            
                            <x-danger-button
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-role-deletion-{{ $role->id }}')"
                            >
                                {{ __('Delete') }}
                            </x-danger-button>
                            <x-modal name="confirm-role-deletion-{{ $role->id }}" :show="false" focusable>
                                <form method="post" action="{{ route('admin.roles.destroy', $role) }}" class="p-6">
                                    @csrf
                                    @method('delete')

                                    <h2>{{ __('Are you sure you want to delete this role?') }}</h2>

                                    <div class="mt-6 flex justify-end">
                                        <button type="button" 
                                                x-on:click="$dispatch('close')"
                                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                                            {{ __('Cancel') }}
                                        </button>
                                        <button type="submit" 
                                                class="ml-2 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                                            {{ __('Delete') }}
                                        </button>
                                    </div>
                                </form>
                            </x-modal>
                        
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-4 text-center text-gray-500">{{ __('No roles found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{-- Role Edit Modal --}}
        <div
            x-show="open" 
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-black/30"
            style="background-color: rgba(0, 0, 0, 0.81);"
            @keydown.escape.window="open = false"
        >
            <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-xl"
                x-transition.scale.opacity.duration.300
                @click.outside="open = false"
            >
                <h2 class="text-xl font-semibold mb-4">{{ __('Edit Roles') }}</h2>
                <form id="edit-role-form" method="POST" :action="`/admin/roles/${roleId}`" x-on:submit.prevent="submitForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700" for="name">
                            {{ __('Role Name') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                            value="{{ old('name') }}"
                            class="mt-2 block w-full border-gray-300 rounded-md shadow-sm"
                            x-model="roleName"
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Permissions') }}
                        </label>

                        <template x-for="permission in availablePermissions" :key="permission">
                            <label class="inline-flex items-center mr-4 mb-2">
                                <input 
                                    type="checkbox" 
                                    name="permissions[]" 
                                    :value="permission" 
                                    x-model="rolePermissions"
                                    class="rounded border-gray-300 shadow-sm text-indigo-600"
                                >
                                <span class="ml-2" x-text="permission"></span>
                            </label>
                        </template>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" @click="open = false" class="px-4 py-2 border rounded transition">{{ __('Cancel') }}</button>
                        <button type="submit" 
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ml-2">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>

            </div>
                <script>
                    function roleEditModal() {
                        return {
                            open: false,
                            roleId: null,
                            roleName: '',
                            rolePermissions: [],
                            availablePermissions: @json(\Spatie\Permission\Models\Permission::pluck('name')),

                            openModal(role) {
                                this.roleId = role.id;
                                this.roleName = role.name;
                                this.rolePermissions = role.permissions;
                                this.open = true;
                            },
                            closeModal() {
                                this.roleId = null;
                                this.roleName = '';
                                this.rolePermissions = [];
                                this.open = false;
                            },

                            submitForm() {
                                const form = document.getElementById('edit-role-form');
                                form.submit();
                            },
                            
                        }
                    }
                </script>     
        </div>
    
    <div class="mt-4">
        {{ $roles->links() }}
    </div>
</div>
