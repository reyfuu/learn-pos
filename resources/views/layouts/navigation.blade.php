<nav x-data="{ open: false }" class="bg-[#1e293b] border-b border-[#334155]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-10">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center gap-2.5 group">
                    <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shadow-lg shadow-brand-900/40 group-hover:shadow-brand-600/40 transition-shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2 8h20v2H2V8zm0 6h20v2H2v-2zm0-3h20v2H2v-2z"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-lg tracking-tight">Kasir<span class="text-brand-400">Pro</span></span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('dashboard') 
                                 ? 'bg-brand-500/10 text-brand-400' 
                                 : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('pos.index') }}"
                       class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('pos.*') 
                                 ? 'bg-brand-500/10 text-brand-400' 
                                 : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        POS
                    </a>
                    <a href="{{ route('products.index') }}"
                       class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('products.*') 
                                 ? 'bg-brand-500/10 text-brand-400' 
                                 : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        Products
                    </a>
                    <a href="{{ route('reports.index') }}"
                       class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('reports.*') 
                                 ? 'bg-brand-500/10 text-brand-400' 
                                 : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        Reports
                    </a>
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center gap-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2.5 pl-3 pr-4 py-2 rounded-xl
                                       text-sm font-medium text-slate-300 hover:text-white
                                       hover:bg-white/5 transition-all duration-150 focus:outline-none">
                            <div class="h-7 w-7 rounded-lg bg-brand-600 flex items-center justify-center text-xs font-bold text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-white/5 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-[#334155]">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-white/5">Dashboard</a>
            <a href="{{ route('pos.index') }}" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-white/5">POS</a>
            <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-white/5">Products</a>
            <a href="{{ route('reports.index') }}" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-white/5">Reports</a>
        </div>

        <div class="pt-4 pb-1 border-t border-[#334155] px-4">
            <div class="font-medium text-base text-slate-200">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-white/5">Profile</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-white/5">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
