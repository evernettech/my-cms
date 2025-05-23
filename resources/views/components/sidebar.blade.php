@props([
    'items' => [
        ['label' => __('Dashboard'), 'route' => 'dashboard'],
        ['label' => __('Blog'), 'route' => 'blogs.index'],
        ['label' => __('User Management'), 'submenu' => [
            ['label' => __('Users'), 'route' => 'admin.users.index'],
            ['label' => __('Roles'), 'route' => 'admin.roles.index'],
        ]],
         ['label' => __('Front End'), 'submenu' => [
            ['label' => __('Telegram'), 'url' => 'http://my-frontend.com/telegram'],
            ['label' => __('Whatsapp'), 'url' => 'http://my-frontend.com/whatsapp'],
        ]],
        ['label' => __('Profile'), 'route' => 'profile.edit'],
        ['label' => __('Logout'), 'action' => 'logout'],
    ]
])

<nav x-data="{ open: true }" 
     x-init="
    open = window.innerWidth >= 1600;
    window.addEventListener('resize', () => {
        open = window.innerWidth >= 1600;
    });
    $watch('open', val => console.log('Sidebar state:', val));
"
     class="navbar-left"
     :style="open 
        ? 'width: 220px; height: 100vh; background: #2d3748; color: #fff; position: fixed; top: 0; left: 0; transition: width 0.3s;' 
        : 'width: 50px; height: 100vh; background: #2d3748; color: #fff; position: fixed; top: 0; left: 0; overflow: hidden; transition: width 0.3s;'" 
     style="z-index: 1000;">

    <!-- Collapse/Expand Button -->
    <button 
        @click="open = !open" 
        :style="open 
            ? 'position: absolute; top: 0px; right: 0px; background: #2d3748; color: #fff; border: none; border-radius: 0 4px 4px 0; width: 40px; height: 40px; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; z-index: 1001;' 
            : 'position: absolute; top: 16px; left: 0; background: #2d3748; color: #fff; border: none; border-radius: 0 4px 4px 0; width: 40px; height: 40px; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; z-index: 1001;'"
    >
        <span x-show="open">&#10094;</span>
        <span x-show="!open">&#10095;</span>
    </button>

    <div x-show="open" style="padding: 24px; font-size: 1.5rem; font-weight: bold; border-bottom: 1px solid #4a5568;">
        My CMS
    </div>

    <ul x-show="open" style="list-style: none; padding: 0; margin: 0;">
        @foreach ($items as $item)
            {{-- Single item with route --}}
            @if (isset($item['route']))
                <li>
                    <a href="{{ route($item['route']) }}" class="block px-6 py-4 text-white hover:bg-gray-700">
                        {{ $item['label'] }}
                    </a>
                </li>

            {{-- Single item with external/custom URL --}}
            @elseif (isset($item['url']))
                <li>
                    <a href="{{ $item['url'] }}" target="_blank" class="block px-6 py-4 text-white hover:bg-gray-700">
                        {{ $item['label'] }}
                    </a>
                </li>

            {{-- Submenu for admin users --}}
            @elseif (isset($item['submenu']) && auth()->user()->hasRole('admin'))
                <li x-data="{ openSubmenu: false }" class="relative">
                    <button @click="openSubmenu = !openSubmenu"
                            class="w-full flex justify-between items-center px-6 py-4 text-white hover:bg-gray-700 focus:outline-none">
                        {{ $item['label'] }}
                        <span x-text="openSubmenu ? '▲' : '▼'"></span>
                    </button>
                    <ul x-show="openSubmenu" class="ml-4 bg-gray-800">
                        @foreach ($item['submenu'] as $subItem)
                            <li>
                                @if (isset($subItem['route']))
                                    <a href="{{ route($subItem['route']) }}"
                                    class="block px-6 py-3 text-sm text-white hover:bg-gray-600">
                                        {{ $subItem['label'] }}
                                    </a>
                                @elseif (isset($subItem['url']))
                                    <a href="{{ $subItem['url'] }}" target="_blank"
                                    class="block px-6 py-3 text-sm text-white hover:bg-gray-600">
                                        {{ $subItem['label'] }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </li>

            {{-- Logout action --}}
            @elseif (isset($item['action']) && $item['action'] === 'logout')
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block text-left px-6 py-4 text-white hover:bg-gray-700">
                            {{ $item['label'] }}
                        </button>
                    </form>
                </li>
            @endif
        @endforeach

    </ul>
</nav>
