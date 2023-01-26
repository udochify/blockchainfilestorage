@props(['errors'])

@if ($errors->any())
    <!-- Validation Errors -->
    <div {{ $attributes }}>
        <div class="font-medium text-sm text-red-600">
            {{ __('Whoops! Something went wrong.') }}
        </div>

        <ul class="mt-1 list-disc list-inside text-xs text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
