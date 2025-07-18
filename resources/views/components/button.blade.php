@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $buttonClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $buttonClasses]) }}>
        {{ $slot }}
    </button>
@endif