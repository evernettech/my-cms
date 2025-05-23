<section>
    <a href="{{ route('blogs.create') }}">
        <x-primary-button>{{ __('Create Blog') }}</x-primary-button>
    </a>
    <a href="{{ route('blogs.trash') }}">
        <x-primary-button>🗑️{{ __('View Trash') }}</x-primary-button>
    </a>
</section>
