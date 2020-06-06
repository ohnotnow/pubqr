<div>
  <section class="text-gray-700 body-font" wire:poll.5s>
    <div class="container px-5 py-2 flex flex-wrap">
      <div class="flex flex-col flex-wrap">
        <span class="flex justify-center">
          <h1 class="text-2xl font-bold tracking-wide pb-8">{{ $orders->count() }} orders pending</h1>
        </span>
        @foreach ($orders as $order)
        <div class="flex flex-col mb-10 lg:items-start items-center shadow-lg p-8 bg-gray-100">
          <span class="text-gray-500 font-light">{{ $order->created_at->diffForHumans() }}</span>
          <div class="flex-grow mt-4">
            <h2 class="text-gray-900 text-lg title-font font-medium mb-3">{{ $order->quantity }} x {{ $order->item->name }}</h2>
            <p>Total : <span class="text-gray-900 title-font font-medium">£{{ $order->cost_in_pounds }}</span></p>
            <p>Contact : <span class="text-gray-900 title-font font-medium">{{ $order->contact }}</span></p>
          </div>
          <div class="flex justify-between w-full">
            <button wire:click.prevent="fulfill({{ $order->id }})" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4">
              Mark as completed
            </button>
            <button wire:click.prevent="cancel({{ $order->id }})" class="hover:text-gray-500 focus:underline text-gray-900 font-bold py-2 px-4 rounded mt-4">
              Cancel
            </button>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>
</div>