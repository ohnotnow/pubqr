<div>
    <section class="text-gray-700 body-font overflow-hidden">
        <div class="container px-5 py-24 mx-auto">
            <div class="lg:w-4/5 mx-auto flex flex-wrap">
                <img alt="ecommerce" class="lg:w-1/2 w-full lg:h-auto h-64 object-cover object-center rounded" src="https://dummyimage.com/400x400">
                <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                    <h2 class="text-sm title-font text-gray-500 tracking-widest">Ride Brewing</h2>
                    <h1 class="text-gray-900 text-3xl title-font font-medium mb-1">{{ $item->name }}</h1>
                    <p class="leading-relaxed">{{ $item->description }}</p>
                    <p class="title-font font-medium text-2xl text-gray-900">£{{ $item->price_in_pounds }}</p>

                    <div class="flex mt-6 items-center pb-5">
                        <label class="block">
                            <span class="text-gray-700">Quantity</span>
                            <input class="form-input mt-1 block w-full" type="number" wire:model="quantity">
                            @error('quantity')
                            <p class="p-2 text-red-500">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>
                    <div class="flex mt-4 items-center pb-5">
                        <label class="block">
                            <span class="text-gray-700">Contact Details <span class="text-gray-500 font-light">(name or mobile)</span></span>
                            <input class="form-input mt-1 block w-full" type="input" wire:model="contact">
                            @error('contact')
                            <p class="p-2 text-red-500">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>
                    <div class="flex mt-6 mb-6 border-b-2 border-gray-200 pb-6">
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox" wire:model="confirmPayment">
                            <span class="ml-2">I am 18 or older and agree to pay £{{ number_format($orderCost / 100, 2) }}</span>
                        </label>
                        @error('confirmPayment')
                        <p class="p-2 text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="lg:w-4/5 mx-auto flex justify-end">
                    <div class="flex">
                        @if ($saved)
                        <div x-data="{ saved: true }" x-init="setTimeout(() => { saved = false; console.log('fred') }, 3000)">
                            <p x-show.transition="saved" class="text-gray-500 py-2 px-6">Order for £{{ number_format($orderCost / 100, 2) }} sent! 🍺</p>
                        </div>
                        @endif
                        <button wire:click.prevent="placeOrder" class="ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded disabled:opacity-25" @if (! $quantity or ! $contact or ! $confirmPayment) disabled="disabled" @endif>Place Order for £{{ number_format($orderCost / 100, 2) }}</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>