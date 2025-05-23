<div>
    <div class="mb-4 flex justify-between items-center" style="column-gap: 20px;">
        <input
            type="text"
            wire:model="search"
            placeholder="{{ __('Search User...') }}"
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

    <div x-data="userEditModal()">
        <table class="min-w-full divide-y divide-gray-200 bg-white text-sm text-gray-700">
            <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                <tr>
                    <th class="px-6 py-3 text-center">{{ __('ID') }}</th>
                    <th class="px-6 py-3 text-center">{{ __('Name') }}</th>
                    <th class="px-6 py-3 text-center">{{ __('Roles') }}</th>
                    <th class="px-6 py-3 text-center">{{ __('Email') }}</th>
                    <th class="px-6 py-3 text-center">{{ __('Created At') }}</th>
                    <th class="px-6 py-3 text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 text-center">{{ $user->id }}</td>
                        <td class="px-6 py-4 text-center">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($user->roles->isNotEmpty())
                                @foreach($user->roles as $role)
                                    <span class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-xs font-semibold mr-2">
                                        {{ __($role->name) }}
                                    </span>
                                @endforeach
                            @else
                                <span class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-xs font-semibold mr-2">
                                    {{ __('operator') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-center">{{ $user->created_at->setTimezone('Asia/Singapore')->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 text-center flex gap-2">
                            <button 
                                type="button"
                                @click="openModal({ 
                                    id: {{ $user->id }}, 
                                    name: '{{ $user->name }}', 
                                    email: '{{ $user->email }}', 
                                    status: '{{ $user->status ?? 'inactive' }}' 
                                })"
                                class="inline-block px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition"
                            >
                                {{ __('Edit') }}
                            </button>
                            
                            <x-danger-button
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion-{{ $user->id }}')"
                            >
                                {{ __('Delete') }}
                            </x-danger-button>
                            <x-modal name="confirm-user-deletion-{{ $user->id }}" :show="false" focusable>
                                <form method="post" action="{{ route('admin.users.destroy', $user) }}" class="p-6">
                                    @csrf
                                    @method('delete')

                                    <h2>{{ __('Are you sure you want to delete this user?') }}</h2>
                                    <p>{{ __('Once deleted, this action cannot be undone.') }}</p>

                                    <div class="mt-6 flex justify-end">
                                        <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                                        <x-danger-button class="ms-3">{{ __('Delete User') }}</x-danger-button>
                                    </div>
                                </form>
                            </x-modal>
                            <!-- Delete button and modal here -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 italic">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Edit Modal -->
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
            <h2 class="text-xl font-semibold mb-4">{{ __('Edit User') }}</h2>

            <form id="edit-user-form" method="POST" :action="`/admin/users/${userId}`" x-on:submit.prevent="submitForm">
                @csrf
                @method('PUT')

                <label class="block mb-2">{{ __('Name') }}</label>
                <input type="text" x-model="userName" name="name" class="border p-2 w-full mb-4" required>

                <label class="block mb-2">{{ __('Email') }}</label>
                <input type="email" x-model="userEmail" name="email" class="border p-2 w-full mb-4" required>

                <label class="block mb-2">{{ __('Password') }}</label>
                <input 
                    type="password" 
                    x-model="userPassword" 
                    name="password" 
                    class="border p-2 w-full mb-4"
                    placeholder="{{ __('Leave blank to keep current password') }}"
                    autocomplete="new-password"
                />

                <label class="block mb-2">{{ __('Status') }}</label>
                <select x-model="userStatus" name="status" class="border p-2 w-full mb-4">
                    <option value=1>{{ __('Active') }}</option>
                    <option value=0>{{ __('Inactive') }}</option>
                </select>

                <div class="flex justify-end space-x-2">
                <button type="button" @click="open = false" class="px-4 py-2 border rounded">{{ __('Cancel') }}</button>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">{{ __('Save') }}</button>
                </div>
            </form>
            </div>
        </div>
        <script>
            function userEditModal() {
                return {
                    open: false,
                    userId: null,
                    userName: '',
                    userEmail: '',
                    userPassword: '',
                    userStatus: '1',
                    
                    openModal(user) {
                        this.userId = user.id;
                        this.userName = user.name;
                        this.userEmail = user.email;
                        this.userPassword = user.password;
                        this.userStatus = user.status ?? '1';
                        this.open = true;
                    },

                    submitForm() {
                        // Use hidden form submit if you prefer
                        const form = document.getElementById('edit-user-form');
                        form.action = `/admin/users/${this.userId}`;
                        form.submit();
                    }
                }
            }
        </script>
    </div>

    <div>
        {{ $users->links() }}
    </div>
</div>

