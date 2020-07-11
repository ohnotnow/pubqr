<header class="text-gray-700 body-font">
  <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
    <a class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0" href="{{ route('home') }}">
        <svg class="w-10 h-10 text-black p-2" viewBox="0 0 18 18" fill="currentColor">
            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
        </svg>
      <span class="ml-3 text-xl">{{ config('app.name') }}</span>
    </a>
    <nav class="md:mr-auto md:ml-4 md:py-1 md:pl-4 md:border-l md:border-gray-400	flex flex-wrap items-center text-base justify-center">
        <a href="{{ route('order.index') }}" class="default-link mr-6 @if (Route::currentRouteName() == 'order.index') border-b-2 @endif">Current Orders</a>
        <a href="{{ route('order.history') }}" class="default-link mr-6 @if (Route::currentRouteName() == 'order.history') border-b-2 @endif">Order History</a>
        <a href="{{ route('customer.index') }}" class="default-link mr-6 @if (Route::currentRouteName() == 'customer.index') border-b-2 @endif">Customers</a>
        <a href="{{ route('inventory.index') }}" class="default-link mr-6 @if (Route::currentRouteName() == 'inventory.index') border-b-2 @endif">Inventory</a>
        <a href="{{ route('user.index') }}" class="default-link mr-6 @if (Route::currentRouteName() == 'user.index') border-b-2 @endif">Staff</a>
        @superadmin
        <a href="{{ route('download.backup') }}" data-turbolinks="false" class="default-link mr-6">Backup</a>
        @livewire('database-restorer')
        @endsuperadmin
    </nav>
    @auth
        <a
            href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="font-medium text-gray-600 hover:text-gray-500 focus:outline-none focus:underline transition ease-in-out duration-150"
        >
            Log out
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endauth
  </div>
</header>
