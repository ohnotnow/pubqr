<div>
    <span x-data="{ focused: false }">
        <form action="">
            <input class="sr-only" @focus="focused = true" @blur="focused = false" id="restore-file" accept=".zip" type="file" wire:model="restoreFile">
            @if ($restoreFile)
                <label id="tweedle" :class=" {'outline-none underline': focused }" class="default-link cursor-pointer" for="restore-file" wire:click.prevent="restore">Restore @error('restoreFile') @else $restoreFile->getClientOriginalName() @enderror</label>
            @else
                <label id="dum" :class=" {'outline-none underline': focused }" class="default-link cursor-pointer" for="restore-file">Restore</label>
            @endif
            @error('restoreFile')
                <span class="text-red-500">Invalid!</span>
            @enderror
        </form>
    </span>
</div>
