<div>
<section class="text-gray-700 body-font">
  @guest
    <a class="float-right p-4 default-link" href="{{ route('login') }}">Log In</a>
  @endguest
  <div class="container px-5 py-2 mx-auto">
    <div class="flex flex-col text-center w-full mb-20">
      <h1 class="text-2xl font-medium title-font mb-4 text-gray-900 tracking-widest @guest mt-8 @endguest">Current Inventory</h1>
      @auth
      <div class="flex justify-center space-x-8">
          <a class="default-link" href="{{ route('item.create') }}">Add new item</a>
          <a class="default-link" data-turbolinks="false" href="{{ route('download.qrcodes') }}">Download QR codes</a>
      </div>
      @endauth
    </div>
    <div class="flex flex-wrap -m-4">
      @foreach ($items->chunk(2) as $items)
        @foreach ($items as $item)
      <div class="p-4 lg:w-1/2">
        <div class="h-full flex sm:flex-row flex-col items-center sm:justify-start justify-center text-center sm:text-left">
          <a href="{{ route('item.show', $item->code) }}" class="flex-shrink-0 rounded-lg w-48 h-48 object-cover object-center sm:mb-0 mb-4">
          <img alt="team" src="https://dummyimage.com/200x200">
          </a>
          <div class="flex-grow sm:pl-8">
            <h2 class="title-font font-medium text-lg text-gray-900 flex justify-between">
                <span>
                <a href="{{ route('item.show', $item->code) }}">{{ $item->name }}</a></span>
                <span>£{{ $item->price_in_pounds }}</span>
            </h2>
            <p class="mb-4">{{ $item->description }}</p>
            @auth
            <span class="flex justify-between">
                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" wire:click="toggleAvailable({{ $item->id }})" @if ($item->isAvailable()) checked @endif>
                    <span class="ml-2">Available?</span>
                </label>
                <a href="{{ route('item.edit', $item->id) }}" class="default-link">
                    Edit
                </a>
            </span>
            @endauth
          </div>
        </div>
      </div>
      @endforeach
      @endforeach
    </div>
  </div>
</section>
</div>
