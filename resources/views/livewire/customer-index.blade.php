<div class="min-h-screen py-2 bg-gray-50 sm:px-6 lg:px-8">
    <div class="container mx-auto">

        <div class="flex justify-between items-center pb-8">
            <h1 class="text-2xl font-bold tracking-wide">Customers</h1>
            <a href="{{ route('order.history.export') }}" data-turbolinks="false" class="default-link">Export to Excel</a>
        </div>

        <div class="flex justify-between items-center pb-8" x-data="{}" x-init="new Pikaday({field: $refs.datepicker, format: 'DD/MM/YYYY'})" @change="$dispatch('input', $event.target.value)">
            <input class="bg-white rounded border border-gray-400 focus:outline-none focus:border-indigo-500 text-base px-4 py-2 mb-4" type="text" name="contact_number" x-ref="datepicker" wire:model="date" placeholder="dd/mm/yyyy">
        </div>

        {{ $customers->links() }}

        <table class="table-auto w-full mb-8">
            <thead>
                <tr>
                    <th class="text-left pl-4">Name</th>
                    <th class="text-left pl-4">Number</th>
                    <th class="text-left pl-4">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                <tr class="hover:bg-gray-100">
                    <td class="border px-4 py-2">
                        {{ decrypt($customer->contact_name) }}
                    </td>
                    <td class="border px-4 py-2">
                        <a href="tel:{{ decrypt($customer->contact_number) }}" class="default-link">
                            {{ decrypt($customer->contact_number) }}
                        </a>
                    </td>
                    <td class="border px-4 py-2">
                        {{ $customer->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $customers->links() }}
    </div>
</div>

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@endpush
