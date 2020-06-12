<div>
<div class="min-h-screen py-2 bg-gray-50 sm:px-6 lg:px-8">
<div class="container mx-auto">

    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold tracking-wide pb-8">Staff</h1>
        <a href="{{ route('user.create') }}" class="default-link">Add New Staff</a>
    </div>
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th class="text-left pl-4">Name</th>
                <th class="text-left pl-4">Email</th>
                <th class="text-left pl-4">Last Login</th>
                @superadmin
                <th class="text-left pl-4"></th>
                <th class="text-left pl-4"></th>
                <th class="text-left pl-4"></th>
                @endsuperadmin
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr class="hover:bg-gray-100">
                <td class="border px-4 py-2">
                    <a href="{{ route('user.edit', $user->id) }}" class="default-link">
                        {{ $user->name }}
                    </a>
                </td>
                <td class="border px-4 py-2">{{ $user->email }}</td>
                <td class="border px-4 py-2">{{ $user->login_at ? $user->login_at->format('d/m/Y H:i') : 'N/A' }}</td>
                @superadmin
                <td class="border px-4 py-2">
                    @if ($user->id != auth()->id())
                    <label for="canlogin_{{ $user->id }}">
                        <input type="checkbox" @if ($user->canLogIn()) checked @endif name="" wire:click="toggleLogIn({{ $user->id }})" id="canlogin_{{ $user->id }}"> Can log in?
                    </label>
                    @endif
                </td>
                <td class="border px-4 py-2">
                    @if ($user->id != auth()->id())
                    <label for="superadmin_{{ $user->id }}">
                        <input type="checkbox" @if ($user->isSuperAdmin()) checked @endif name="" wire:click="toggleSuperAdmin({{ $user->id }})" id="superadmin_{{ $user->id }}"> Is a superadmin?
                    </label>
                    @endif
                </td>
                <td class="border px-4 py-2">
                    @if ($user->id != auth()->id())
                    <button wire:click="deleteUser({{ $user->id }})"><span class="text-red-500">Delete</span></button>
                    @endif
                </td>
                @endsuperadmin
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
