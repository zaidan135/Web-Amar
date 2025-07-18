@props([
    'name' => 'search', // Nilai default untuk atribut 'name'
    'id' => 'search',   // Nilai default untuk atribut 'id'
    'placeholder' => 'Cari...', // Nilai default untuk placeholder
    'value' => '' // Nilai default untuk value (berguna untuk menampilkan keyword pencarian sebelumnya)
])

<div class="relative w-full">
    {{-- Label untuk aksesibilitas, terhubung dengan 'id' --}}
    <label for="{{ $id }}" class="sr-only">{{ $placeholder }}</label>

    {{-- Input field yang sebenarnya --}}
    <input type="search"
           name="{{ $name }}"
           id="{{ $id }}"
           placeholder="{{ $placeholder }}"
           value="{{ $value }}"
           {{-- 'merge' akan menggabungkan class default dengan class tambahan dari luar --}}
           {{ $attributes->merge([
                'class' => 'block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm text-gray-900 transition-colors
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-300'
           ]) }}
    >
</div>