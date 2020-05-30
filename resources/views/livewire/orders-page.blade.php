<div>
<section class="text-gray-700 body-font" wire:poll.5s>
  <div class="container px-5 py-24 flex flex-wrap">
    <div class="flex flex-col flex-wrap">
      @foreach ($orders as $order)
        <div class="flex flex-col mb-10 lg:items-start items-center shadow-lg p-8 bg-gray-100">
            <span class="text-gray-500 font-light">{{ $order->created_at->diffForHumans() }}</span>
            <div class="flex-grow mt-4">
            <h2 class="text-gray-900 text-lg title-font font-medium mb-3">{{ $order->quantity }} x {{ $order->item->name }}</h2>
            <p>Total : <span class="text-gray-900 title-font font-medium">£{{ $order->cost_in_pounds }}</span></p>
            <p>Contact : <span class="text-gray-900 title-font font-medium">{{ $order->contact }}</span></p>
            </div>
            <button wire:click.prevent="fulfill({{ $order->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                Mark as completed
            </button>
        </div>
      @endforeach
    </div>
  </div>
</section>
</div>
