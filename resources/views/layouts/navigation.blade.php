<nav class="bg-white border-b border-gray-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16">

            {{-- LEFT: Logo --}}
            <div class="flex items-center">

                <a href="{{ url('/') }}"
                   class="text-xl font-bold text-gray-800">
                    BMRC Journal
                </a>

            </div>


            {{-- RIGHT --}}
            <div class="flex items-center">

                @auth

                    {{-- Authenticated User --}}
                    <div class="relative">

                        <button
                            type="button"
                            class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900"
                            onclick="document.getElementById('user-menu').classList.toggle('hidden')">

                            {{ Auth::user()->name }}

                            <svg class="ml-2 h-4 w-4"
                                 xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7" />

                            </svg>

                        </button>


                        {{-- Dropdown --}}
                        <div id="user-menu"
                             class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-md shadow-lg z-50">


                            {{-- User Information --}}
                            <div class="px-4 py-3 border-b">

                                <div class="font-medium text-gray-900">
                                    {{ Auth::user()->name }}
                                </div>

                                <div class="text-sm text-gray-500">
                                    {{ Auth::user()->email }}
                                </div>

                                <div class="text-xs text-gray-400 mt-1">
                                    {{ ucfirst(Auth::user()->user_type ?? 'User') }}
                                </div>

                            </div>


                            {{-- ============================= --}}
                            {{-- EXTERNAL USER --}}
                            {{-- ============================= --}}

                            @if(Auth::user()->user_type === 'external')

                                {{-- AUTHOR --}}
                                @if(Auth::user()->authorProfile)

                                    <a href="{{ route('author.dashboard') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">

                                        Author Dashboard

                                    </a>

                                    <a href="{{ route('author.profile.edit') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">

                                        My Profile

                                    </a>

                                @endif


                                {{-- Reviewer will be added later --}}
                                {{--

                                @if(Auth::user()->reviewerProfile)

                                    <a href="{{ route('reviewer.dashboard') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Reviewer Dashboard
                                    </a>

                                @endif

                                --}}

                            @endif


                            {{-- ============================= --}}
                            {{-- INTERNAL USER --}}
                            {{-- ============================= --}}

                            @if(Auth::user()->user_type === 'internal')

                                @role('super-admin')

                                    <a href="{{ url('/admin/dashboard') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">

                                        Super Admin Dashboard

                                    </a>

                                @endrole


                                @role('admin')

                                    <a href="{{ url('/admin/dashboard') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">

                                        Admin Dashboard

                                    </a>

                                @endrole


                                @role('manager')

                                    <a href="{{ url('/manager/dashboard') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">

                                        Manager Dashboard

                                    </a>

                                @endrole


                                @role('editor')

                                    <a href="{{ url('/editor/dashboard') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">

                                        Editor Dashboard

                                    </a>

                                @endrole

                            @endif


                            {{-- ============================= --}}
                            {{-- LOGOUT --}}
                            {{-- ============================= --}}

                            <div class="border-t">

                                <form method="POST"
                                      action="{{ route('logout') }}">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">

                                        Logout

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>


                @else

                    {{-- ============================= --}}
                    {{-- GUEST --}}
                    {{-- ============================= --}}

                    <div class="flex items-center gap-4">

                        <a href="{{ route('author.login') }}"
                           class="text-sm text-gray-700 hover:text-gray-900">

                            Author Login

                        </a>


                        <a href="{{ route('author.register') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">

                            Author Registration

                        </a>

                    </div>

                @endauth

            </div>

        </div>

    </div>

</nav>
