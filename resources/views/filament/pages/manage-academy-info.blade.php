<x-filament-panels::page>
    <form wire:submit="save" class="grid gap-y-6">
        {{ $this->form }}

        <x-filament::actions
            :actions="$this->getFormActions()"
            alignment="start"
            class="mt-4"
        />
    </form>
</x-filament-panels::page>