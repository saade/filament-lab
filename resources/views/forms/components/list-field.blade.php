<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @php
        $editAction = $getAction('edit');
    @endphp
    <ul class="space-y-2">
        @foreach ($getCachedExistingRecords() as $book)
            <li class="flex items-center justify-between border rounded-lg p-2">
                {{ $book->title }}
                
                <div>{{ $editAction(['id' => $book->id]) }}</div>
            </li>
        @endforeach
    </ul>
</x-dynamic-component>
