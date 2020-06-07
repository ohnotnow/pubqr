<div>
<section class="text-gray-700 body-font overflow-hidden">
        <div class="container px-5 py-24 mx-auto">
            <div class="lg:w-4/5 mx-auto flex flex-wrap">
                <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                    <h2 class="text-sm title-font text-gray-500 tracking-widest">@if ($editingExistingUser) Edit User @else Add a New User @endif</h2>
                    <div class="flex mt-6 items-center pb-5">
                        <label class="block">
                            <span class="text-gray-700">Name</span>
                            <input class="form-input mt-1 block w-full" type="input" wire:model="user.name">
                            @error('user.name')
                            <p class="p-2 text-red-500">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>

                    <div class="flex mt-6 items-center pb-5">
                        <label class="block">
                            <span class="text-gray-700">Email</span>
                            <input class="form-input mt-1 block w-full" type="input" wire:model="user.email">
                            @error('user.email')
                            <p class="p-2 text-red-500">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>

                    @if ($editingExistingUser)
                    <div class="flex mt-6 items-center pb-5">
                        <label class="block">
                            <span class="text-gray-700">Force Password Reset?</span>
                            <input class="form-input mt-1 block" type="checkbox" wire:model="reset_password">
                        </label>
                    </div>
                    @endif
                </div>
                <div class="lg:w-4/5 mx-auto flex justify-end">
                    <button wire:click.prevent="save" class="ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded disabled:opacity-25">Save</button>
                </div>
            </div>
        </div>
    </section>
</div>
