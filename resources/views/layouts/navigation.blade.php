<nav x-data="{ open: false, theme: localStorage.getItem('smartshop-theme') || 'dark', toggleTheme() { this.theme = this.theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('smartshop-theme', this.theme); document.documentElement.classList.toggle('dark', this.theme === 'dark'); } }" x-init="document.documentElement.classList.toggle('dark', theme === 'dark')" class="glass-nav">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex items-center gap-6">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 font-black tracking-tight text-slate-950 dark:text-white">
                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-slate-950 text-white dark:bg-white dark:text-slate-950">S</span>
                        <span>SmartShop AI</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Shop') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('For You') }}
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('wishlist.index')" :active="request()->routeIs('wishlist.*')">
                            {{ __('Wishlist') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <button type="button" @click="toggleTheme" class="theme-toggle" :aria-label="theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'">
                    <svg x-show="theme === 'dark'" x-cloak class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.36-6.36-1.42 1.42M7.06 16.94l-1.42 1.42m12.72 0-1.42-1.42M7.06 7.06 5.64 5.64"/>
                        <circle cx="12" cy="12" r="4"/>
                    </svg>
                    <svg x-show="theme !== 'dark'" x-cloak class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.8A8.5 8.5 0 1 1 11.2 3 6.5 6.5 0 0 0 21 12.8Z"/>
                    </svg>
                </button>
                <a href="{{ route('cart.index') }}" class="btn-soft !px-4 !py-2">Cart</a>
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white dark:bg-white dark:text-slate-950">
                                <span>{{ Auth::user()->name }}</span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if(Auth::user()->isAdmin())
                                <x-dropdown-link :href="route('admin.dashboard')">{{ __('Admin Analytics') }}</x-dropdown-link>
                            @endif
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="btn-soft !px-4 !py-2">Log in</a>
                    <a href="{{ route('register') }}" class="btn-primary !px-4 !py-2">Join free</a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <button type="button" @click="toggleTheme" class="mx-4 mb-2 flex w-[calc(100%-2rem)] items-center justify-center gap-2 rounded-full border border-white/60 bg-white/50 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm backdrop-blur-xl dark:border-white/10 dark:bg-white/5 dark:text-slate-100">
                <span x-text="theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"></span>
            </button>
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">{{ __('Home') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">{{ __('Shop') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('For You') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')">{{ __('Cart') }}</x-responsive-nav-link>
        </div>

        @auth
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
            <div class="space-y-1 border-t border-slate-200 py-3 dark:border-white/10">
                <x-responsive-nav-link :href="route('login')">{{ __('Log in') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">{{ __('Register') }}</x-responsive-nav-link>
            </div>
        @endauth
    </div>
</nav>
