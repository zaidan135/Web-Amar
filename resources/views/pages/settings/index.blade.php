<x-layout>
    <div class="p-6 sm:p-8">
        <div class="mx-auto max-w-lg">
            
            <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Pengaturan Cetak</h1>
            <p class="mt-2 text-gray-500">
                Pilih metode pencetakan struk default untuk akun Anda.
            </p>

            @if (session('success'))
                <div class="mt-4 rounded-lg bg-green-100 p-4 text-sm text-green-700" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('print.setting.save') }}" method="POST" class="mt-8 space-y-6">
                @csrf
                
                <fieldset>
                    <legend class="text-lg font-medium text-gray-900">Mode Cetak</legend>
                    <div class="mt-4 space-y-2">
                        <div>
                            <label class="flex items-center gap-3 rounded-lg border border-gray-200 p-4 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="print_mode" value="pdf" class="h-5 w-5 border-gray-300 text-blue-600 focus:ring-blue-500" 
                                       {{ auth()->user()->print_mode == 'pdf' ? 'checked' : '' }}>
                                <div>
                                    <strong class="font-medium text-gray-900">Cetak ke PDF</strong>
                                    <p class="text-sm text-gray-500">Membuka struk dalam format PDF di tab baru. Direkomendasikan untuk fleksibilitas.</p>
                                </div>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center gap-3 rounded-lg border border-gray-200 p-4 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="print_mode" value="direct" class="h-5 w-5 border-gray-300 text-blue-600 focus:ring-blue-500"
                                       {{ auth()->user()->print_mode == 'direct' ? 'checked' : '' }}>
                                <div>
                                    <strong class="font-medium text-gray-900">Cetak Langsung (Thermal)</strong>
                                    <p class="text-sm text-gray-500">Memicu dialog cetak browser untuk printer kasir (thermal). Membutuhkan nama printer.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </fieldset>

                <div>
                    <label for="printer_name" class="block text-sm font-medium text-gray-700">Nama Printer (Opsional)</label>
                    <input type="text" id="printer_name" name="printer_name" 
                           value="{{ old('printer_name', auth()->user()->printer_name) }}"
                           placeholder="Contoh: EPSON TM-T82"
                           class="mt-1 w-full rounded-md border-gray-200 shadow-sm sm:text-sm">
                    <p class="mt-1 text-xs text-gray-500">Isi jika Anda menggunakan mode Cetak Langsung. Nama harus sama persis dengan yang terdaftar di komputer.</p>
                </div>

                <div>
                    <button type="submit" class="w-full rounded-lg bg-black px-5 py-3 text-sm font-medium text-white">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
