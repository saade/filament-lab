<x-filament-panels::page>

    <ul>
        @foreach ($this->users as $user)
            <li>{{ $user->name }} {{ ($this->editAction)(['id' => $user->id]) }}</li>
        @endforeach
    </ul>

</x-filament-panels::page>
