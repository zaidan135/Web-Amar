@props([
    'name' => 'search', // Nilai default untuk atribut 'name'
    'id' => 'search',   // Nilai default untuk atribut 'id'
    'placeholder' => 'Cari...', // Nilai default untuk placeholder
    'value' => '' // Nilai default untuk value (berguna untuk menampilkan keyword pencarian sebelumnya)
])

<div class="relative w-full">
    {{-- Label untuk aksesibilitas, terhubung dengan 'id' --}}
    <label for="{{ $id }}" class="sr-only">{{ $placeholder }}</label>

    {{-- Ikon --}}
    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
        </svg>
    </div>

    {{-- Input field yang sebenarnya --}}
    <input type="search"
           name="{{ $name }}"
           id="{{ $id }}"
           placeholder="{{ $placeholder }}"
           value="{{ $value }}"
           {{-- 'merge' akan menggabungkan class default dengan class tambahan dari luar --}}
           {{ $attributes->merge([
                'class' => 'block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 pl-10 text-sm text-gray-900 transition-colors
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-300'
           ]) }}
    >
</div>