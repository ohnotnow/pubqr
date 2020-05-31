<div>
<section class="text-gray-700 body-font">
  <div class="container px-5 py-24 mx-auto">
    <div class="flex flex-col text-center w-full mb-20">
      <h1 class="text-2xl font-medium title-font mb-4 text-gray-900 tracking-widest">Current Inventory</h1>
      <div class="flex justify-center space-x-8">
          <a class="default-link" href="{{ route('item.create') }}">Add new item</a>
          <a class="default-link" href="{{ route('download.qrcodes') }}">Download QR codes</a>
      </div>
    </div>
    <div class="flex flex-wrap -m-4">
      @foreach ($items->chunk(2) as $items)
        @foreach ($items as $item)
      <div class="p-4 lg:w-1/2">
        <div class="h-full flex sm:flex-row flex-col items-center sm:justify-start justify-center text-center sm:text-left">
          <img alt="team" class="flex-shrink-0 rounded-lg w-48 h-48 object-cover object-center sm:mb-0 mb-4" src="https://dummyimage.com/200x200">
          <div class="flex-grow sm:pl-8">
            <h2 class="title-font font-medium text-lg text-gray-900 flex justify-between">
                <span>{{ $item->name }}</span>
                <span>£{{ $item->price_in_pounds }}</span>
            </h2>
            <p class="mb-4">{{ $item->description }}</p>
            <span class="flex justify-between">
                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" wire:click="toggleAvailable({{ $item->id }})" @if ($item->isAvailable()) checked @endif>
                    <span class="ml-2">Available?</span>
                </label>
                <a href="{{ route('item.edit', $item->id) }}" class="default-link">
                    Edit
                </a>
            </span>
          </div>
        </div>
      </div>
      @endforeach
      @endforeach
    </div>
  </div>
</section>
</div>
