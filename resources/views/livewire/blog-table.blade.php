<div>
    <div class="mb-4 flex justify-between items-center" style="column-gap: 20px;">
        <input
            type="text"
            wire:model="search"
            placeholder="Search blogs..."
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
                <option value="{{ $size }}">{{ $size }} per page</option>
            @endforeach
        </select>
    </div>

    <table class="min-w-full divide-y divide-gray-200 bg-white text-sm text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
            <tr>
                <th class="px-6 py-3 text-center">ID</th>
                <th class="px-6 py-3 text-center">Title</th>
                <th class="px-6 py-3 text-center">Created At</th>
                <th class="px-6 py-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($blogs as $blog)
                <tr>
                    <td class="px-6 py-4 text-center">{{ $blog->id }}</td>
                    <td class="px-6 py-4 text-center">{{ $blog->title }}</td>
                    <td class="px-6 py-4 text-center">{{ $blog->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        <a href="{{ route('blogs.edit', $blog) }}"
                           class="inline-block px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition">
                            {{ __('Edit') }}
                        </a>
                        
                        <x-danger-button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-blog-deletion-{{ $blog->id }}')"
                        >
                            {{ __('Delete') }}
                        </x-danger-button>
                        <x-modal name="confirm-blog-deletion-{{ $blog->id }}" :show="false" focusable>
                            <form method="post" action="{{ route('blogs.destroy', $blog) }}" class="p-6">
                                @csrf
                                @method('delete')

                                <h2>{{ __('Are you sure you want to delete this blog?') }}</h2>
                                <p>{{ __('Once deleted, this action cannot be undone.') }}</p>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                                    <x-danger-button class="ms-3">{{ __('Delete Blog') }}</x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                        <!-- Delete button and modal here -->
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-gray-500 italic">No blogs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $blogs->links() }}
    </div>
</div>
