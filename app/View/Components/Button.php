<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public string $buttonClasses;
    
    /**
     * Membuat instance komponen baru.
     *
     * @param string $intent Warna tombol (primary, secondary, danger).
     * @param string $size Ukuran tombol (sm, md, lg).
     * @param string $variant Gaya tombol (solid, outline).
     * @param string|null $href Jika diisi, tombol akan menjadi tag <a>.
     * @param string $type Tipe tombol (button, submit, reset).
     */
    public function __construct(
        public string $intent = 'primary',
        public string $size = 'md',
        public string $variant = 'solid',
        public ?string $href = null,
        public string $type = 'button'
    ) {
        $this->buttonClasses = $this->buildClasses();
    }

    /**
     * Membangun string kelas CSS berdasarkan variasi monokrom.
     */
    protected function buildClasses(): string
    {
        // Kelas dasar yang selalu ada
        $baseClasses = 'inline-flex items-center justify-center rounded-[8px] border text-[14px] font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';

        // Kelas untuk ukuran (tidak berubah)
        $sizeClasses = match ($this->size) {
            'sm' => 'px-3 py-1.5 h-9',
            'lg' => 'px-8 py-3 h-12',
            default => 'px-4 py-2 h-10', // md
        };

        // Kelas untuk varian (solid vs outline)
        $variantClasses = match ($this->variant) {
            'outline' => match ($this->intent) {
                'primary' => 'border-black text-black hover:bg-black hover:text-white focus:ring-black',
                'secondary' => 'border-gray-300 text-gray-800 hover:bg-gray-100 focus:ring-gray-300',
                'danger' => 'border-red-700 text-red-700 hover:bg-red-50 focus:ring-red-600', // BARIS BARU
                default => 'border-black text-black hover:bg-black hover:text-white focus:ring-black',
            },
            default => match ($this->intent) { // solid
                'primary' => 'border-transparent bg-black text-white hover:bg-gray-800 focus:ring-black',
                'secondary' => 'border-transparent bg-[#E8E8E8] text-black hover:bg-gray-300 focus:ring-gray-400',
                'danger' => 'border-transparent bg-red-700 text-white hover:bg-red-800 focus:ring-red-700', // BARIS BARU
                default => 'border-transparent bg-black text-white hover:bg-gray-800 focus:ring-black',
            },
        };

        return "{$baseClasses} {$sizeClasses} {$variantClasses}";
    }

    public function render(): View|Closure|string
    {
        return view('components.button');
    }
}