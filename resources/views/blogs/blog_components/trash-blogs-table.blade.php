@php
    function sortUrl($field, $currentField, $currentDirection) {
        $direction = ($currentField === $field && $currentDirection === 'asc') ? 'desc' : 'asc';
        return request()->fullUrlWithQuery(['sort' => $field, 'direction' => $direction]);
    }

    function sortIcon($field, $currentField, $currentDirection) {
        if ($field !== $currentField) return '';
        return $currentDirection === 'asc' ? '↑' : '↓';
    }
@endphp

<div class="overflow-x-auto rounded-lg shadow mt-8">
    {{-- search function --}}
    <form method="GET" action="{{ route('blogs.index') }}" class="mb-4 w-full px-4">
        <div class="flex flex-wrap justify-between items-center gap-4 py-6">

            {{-- Left Side: Search + Buttons --}}
            <div class="flex items-center gap-4 flex-wrap">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('Search blogs...') }}"
                    class="px-4 py-2 border rounded w-full sm:w-auto max-w-xs"
                />
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="border bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition whitespace-nowrap"> 
                        🔍 {{ __('Search') }}
                    </button>

                    <a href="{{ route('blogs.index') }}"
                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition whitespace-nowrap">
                        ⟳ {{ __('Reset') }}
                    </a>
                </div>
            </div>

            {{-- Right Side: Per Page Selector --}}
            <div class="flex items-center gap-2">
                <label for="perPage" class="text-sm whitespace-nowrap">{{ __('Show') }}</label>
                <select name="perPage" id="perPage" onchange="this.form.submit()"
                        class="px-3 py-2 border rounded bg-white text-sm">
                    @foreach ([10, 20, 30, 50, 100] as $size)
                        <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                            {{ $size }} {{ __('per page') }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </form>
    {{-- blogs table --}}
    <table class="min-w-full divide-y divide-gray-200 bg-white text-sm text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
            <tr>
                <th class="px-6 py-3 text-left">
                    <a href="{{ sortUrl('id', $sortField, $sortDirection) }}" class="hover:underline flex items-center gap-1">
                        {{ __('ID') }} <span>{{ sortIcon('id', $sortField, $sortDirection) }}</span>
                    </a>
                </th>
                <th class="px-6 py-3 text-left">{{ __('Title') }}</th>
                <th class="px-6 py-3 text-left">
                    <a href="{{ sortUrl('created_at', $sortField, $sortDirection) }}" class="hover:underline flex items-center gap-1">
                        {{ __('Created At') }} <span>{{ sortIcon('created_at', $sortField, $sortDirection) }}</span>
                    </a>
                </th>
                <th class="px-6 py-3 text-left">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($blogs as $blog)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $blog->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $blog->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $blog->created_at->timezone('Asia/Singapore')->format('Y-m-d H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
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
                        

                        <!-- Modal for this blog -->
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
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">
                        {{ __('No blogs found.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{-- pagination --}}
    <div class="mt-4">
        {{ $blogs->links('pagination::tailwind') }}
    </div>
</div>
