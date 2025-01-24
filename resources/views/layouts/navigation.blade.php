<x-splade-toggle>
    <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link href="{{ route('dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </Link>
                    </div>

                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <x-dropdown placement="bottom-end">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                                <div>{{ Auth::user()->first_name }}</div>


                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">

                            @forelse ($emailAccounts as $account)
                                @if (session()->get('chosen_email_account','') == $account->id)
                                    <x-dropdown-link class="bg-gray-200 font-bold w-full">
                                        {{ $account->email_address }}
                                    </x-dropdown-link>
                                @else
                                    <x-dropdown-link :href="route('setEmailAccount', $account->id )">
                                        {{ $account->email_address }}
                                    </x-dropdown-link>
                                @endif

                            @empty
                                <x-dropdown-link :href="route('email_accounts.index')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 float-left">
                                        <path fill-rule="evenodd" d="M4.784 3A2.25 2.25 0 0 0 2.68 4.449L1.147 8.475A2.25 2.25 0 0 0 1 9.276v1.474A2.25 2.25 0 0 0 3.25 13h9.5A2.25 2.25 0 0 0 15 10.75V9.276c0-.274-.05-.545-.147-.801l-1.534-4.026A2.25 2.25 0 0 0 11.216 3H4.784Zm-.701 1.983a.75.75 0 0 1 .7-.483h6.433a.75.75 0 0 1 .701.483L13.447 9h-2.412a1 1 0 0 0-.832.445l-.406.61a1 1 0 0 1-.832.445h-1.93a1 1 0 0 1-.832-.445l-.406-.61A1 1 0 0 0 4.965 9H2.553l1.53-4.017Z" clip-rule="evenodd" />
                                    </svg>

                                    <p class="w-48"> {{ __('Email Accounts') }} </p>
                                </x-dropdown-link>
                            @endforelse

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link as="a" class="text-red-900" :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>

                        </x-slot>

                    </x-dropdown>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="toggle" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path v-bind:class="{ hidden: toggled, 'inline-flex': !toggled }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-bind:class="{ hidden: !toggled, 'inline-flex': toggled }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div v-bind:class="{ block: toggled, hidden: !toggled }" class="sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->first_name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link as="a" :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</x-splade-toggle>
