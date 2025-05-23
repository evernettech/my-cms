<div>
    <table class="min-w-full divide-y divide-gray-200 bg-white text-sm text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
            <tr>
                <th class="px-6 py-3 text-left">ID</th>
                <th class="px-6 py-3 text-left">Title</th>
                <th class="px-6 py-3 text-left">Created At</th>
                <th class="px-6 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($blogs as $blog)
                <tr>
                    <td class="px-6 py-4">{{ $blog->id }}</td>
                    <td class="px-6 py-4">{{ $blog->title }}</td>
                    <td class="px-6 py-4">{{ $blog->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('blogs.restore', $blog->id) }}">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition">
                                    {{ __('Recover') }}
                                </button>
                            </form>

                            <x-danger-button
                                x-data
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-blog-deletion-{{ $blog->id }}')"
                            >
                                {{ __('Delete') }}
                            </x-danger-button>
                        </div>

                        <x-modal name="confirm-blog-deletion-{{ $blog->id }}" :show="false" focusable>
                            <form method="post" action="{{ route('blogs.force-delete', $blog) }}" class="p-6">
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
                    <td colspan="4" class="text-center text-gray-500 italic">No blogs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
