<nav x-data="{ open: true }" class="navbar-left" :style="open 
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
        <li style="border-bottom: 1px solid #4a5568;">
            <a href="{{ route('dashboard') }}" style="display: block; padding: 16px 24px; color: #fff; text-decoration: none;">{{ __('Dashboard') }}</a>
        </li>
        <li style="border-bottom: 1px solid #4a5568;">
            <a href="{{ route('blogs.index') }}" style="display: block; padding: 16px 24px; color: #fff; text-decoration: none;">Blogs</a>
        </li>

        <li style="border-bottom: 1px solid #4a5568;">
            <a href="{{ route('profile.edit') }}" style="display: block; padding: 16px 24px; color: #fff; text-decoration: none;">Profile</a>
        </li>

        <li style="border-bottom: 1px solid #4a5568;">
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" style="display: block; padding: 16px 24px; width: 100%; color: #fff; text-align: left; background: none; border: none; text-decoration: none; cursor: pointer;">
                    Logout
                </button>
            </form>
        </li>

    </ul>
</nav>

<!-- Add Alpine.js for interactivity -->
<script src="//unpkg.com/alpinejs" defer></script>
<style>
@media (max-width: 768px) {
    .navbar-left {
        width: 0 !important;
        overflow: hidden;
    }
   
}
</style>