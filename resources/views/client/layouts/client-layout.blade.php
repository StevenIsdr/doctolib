<div class="mt-[3.7811rem]" x-data="{ sidebarOpen: false, darkMode: false, modelOpen: false }">
    <div class="flex h-screen z-30 bg-gray-100 font-roboto">
        <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false"
             class="fixed z-30 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>

        <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
             class="fixed inset-y-0 overflow-hidden left-0 w-60 lg:z-10 z-30 transition duration-300 transform bg-white shadow-lg overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
            <nav class="flex flex-col  w-auto px-7 pt-4 pb-6">
                <!-- SideNavBar -->

                <div class="flex flex-row border-b items-center justify-between pb-2">
                    <!-- Hearder -->
                    <span class="text-lg font-bold font-maven whitespace-nowrap">
				Mon Espace Client
			</span>

                    <span class="relative ">

			</span>

                </div>

                <div class="mt-8">
                    <!-- User info -->
                    {{--                    <img--}}
                    {{--                        class="h-12 w-12 rounded-full object-cover"--}}
                    {{--                        src="https://appzzang.me/data/editor/1608/f9c387cb6bd7a0b004f975cd92cbe2d9_1471626325_6802.png"--}}
                    {{--                        alt="enoshima profile" />--}}
                    <span
                        class="mt-4 text-lg font-bold font-maven capitalize">
                        Hello {{auth()->user()->name}}
                    </span>
                    <span class="text-sm ">
				<span class="font-bold font-maven text-cyan-600 ">
					Client
				</span>
			</span>
                </div>

                <ul class="mt-2">
                    <!-- Links -->
                    <li class="mt-8">
                        <a href="/espace-client/mes-articles" class="flex items-center shadow px-3 py-2 rounded-lg @if(request()->path() == "espace-client/mes-articles") bg-cyan-600 text-white @else hover:scale-105 transition hover:bg-cyan-600 hover:text-white @endif">
                            <svg
                                class="fill-current h-5 w-5"
                                viewBox="0 0 24 24">
                                <path
                                    d="M16 20h4v-4h-4m0-2h4v-4h-4m-6-2h4V4h-4m6
							4h4V4h-4m-6 10h4v-4h-4m-6 4h4v-4H4m0 10h4v-4H4m6
							4h4v-4h-4M4 8h4V4H4v4z"></path>
                            </svg>
                            <span
                                class="ml-2 capitalize font-medium whitespace-nowrap
						">
						Mes rendez vous
					</span>
                        </a>
                    </li>

                    <li class="mt-8">
                        <a href="/espace-client/mes-articles" class="flex items-center shadow px-3 py-2 rounded-lg  @if(request()->path() == "espace-client/mes") bg-cyan-600 text-white @else hover:scale-105 transition hover:bg-cyan-600 hover:text-white @endif ">
                            <svg
                                class="fill-current h-5 w-5 "
                                viewBox="0 0 24 24">
                                <path
                                    d="M19 19H5V8h14m-3-7v2H8V1H6v2H5c-1.11 0-2 .89-2
							2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0
							00-2-2h-1V1m-1 11h-5v5h5v-5z"></path>
                            </svg>
                            <span
                                class="ml-2 capitalize font-medium
						">
						calendar
					</span>
                        </a>
                    </li>


                </ul>

                <div class="mt-auto items-center px-3 py-2 text-orange mt-64 cursor-pointer transition hover:scale-105">
                    <!-- important action -->

                </div>
                <div class="items-center px-3 py-2 text-red mt-6">
                    <!-- important action -->
                    <a href="#home" class="flex items-center">
                        <svg class="fill-current h-5 w-5" viewBox="0 0 24 24">
                            <path
                                d="M16 17v-3H9v-4h7V7l5 5-5 5M14 2a2 2 0 012
						2v2h-2V4H5v16h9v-2h2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V4a2 2
						0 012-2h9z"></path>
                        </svg>
                        <span class="ml-2 capitalize font-medium">déconnexion</span>
                    </a>

                </div>
            </nav>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center p-6">
                <div class="flex items-center space-x-4 lg:space-x-0">
                    <button @click="sidebarOpen = true"
                            class="text-gray-500 focus:outline-none lg:hidden">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div>
                        <span class="text-2xl font-bold font-maven">Vue d'ensemble</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = ! dropdownOpen"
                                class="flex items-center space-x-2 relative focus:outline-none">
                            <span class="font-maven font-bold text-sm hidden sm:block">{{auth()->user()->name}}</span>
                            <img class="h-9 w-9 rounded-full border-2 border-orange object-cover"
                                 src="https://images.unsplash.com/photo-1553267751-1c148a7280a1?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=634&q=80"
                                 alt="Your avatar">
                        </button>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto">
                <div class="container mx-auto px-6 py-8">
                    <div class="">
                        @yield('client-content')
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
