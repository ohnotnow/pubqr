<div>
<section class="text-gray-700 body-font overflow-hidden">
        <div class="container px-5 py-24 mx-auto">
            <div class="lg:w-4/5 mx-auto flex flex-wrap">
                <img alt="ecommerce" class="lg:w-1/2 w-full lg:h-auto h-64 object-cover object-center rounded" src="{{ $this->getImageUrl() }}">
                <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                    <h2 class="text-sm title-font text-gray-500 tracking-widest">@if ($editingExistingItem) Edit Item @else Add a new item @endif</h2>
                    @if ($editingExistingItem)
                        <button>
                            {{ $deleteButtonText }}
                        </button>
                    @endif
                    <div class="flex mt-6 items-center pb-5">
                        <label class="block">
                            <span class="text-gray-700">Name</span>
                            <input class="form-input mt-1 block w-full" type="input" wire:model="item.name">
                            @error('item.name')
                            <p class="p-2 text-red-500">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>

                    <div class="flex mt-6 items-center pb-5">
                        <label class="block">
                            <span class="text-gray-700">Description</span>
                            <textarea class="form-input mt-1 block w-full" wire:model="item.description"></textarea>
                            @error('item.description')
                            <p class="p-2 text-red-500">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>

                    <div class="flex mt-6 items-center pb-5">
                        <label class="block">
                            <span class="text-gray-700">Price</span>
                            <input class="form-input mt-1 block w-full" type="number" wire:model="price_in_pounds">
                            @error('price_in_pounds')
                            <p class="p-2 text-red-500">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>

                    <div class="flex mt-6 items-center pb-5">
                        <label class="block">
                            <span class="text-gray-700">Picture</span>
                            <input class="form-input mt-1 block w-full" type="file" wire:model="newImage">
                            @error('item.image')
                            <p class="p-2 text-red-500">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>

                </div>
                <div class="lg:w-4/5 mx-auto flex justify-end">
                    <button wire:click.prevent="saveItem" class="ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded disabled:opacity-25">Save</button>
                </div>
            </div>
        </div>
    </section>
</div>
