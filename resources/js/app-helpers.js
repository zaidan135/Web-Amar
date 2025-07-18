import axios from 'axios';

/**
 * Utilitas untuk mencetak langsung via QZ Tray.
 * @returns {object}
 */
function qzTrayPrinter() {
    return {
        async tryPrint(raw, printerName) {
            if (typeof qz === 'undefined') {
                alert('QZ Tray tidak terdeteksi. Pastikan aplikasi QZ Tray berjalan.');
                return false;
            }
            try {
                await qz.websocket.connect({ retries: 3, delay: 1000 });
                const printer = await qz.printers.find(printerName);
                if (!printer) {
                    throw new Error(`Printer "${printerName}" tidak ditemukan.`);
                }
                const config = qz.configs.create(printer, { charset: 'UTF-8' });
                await qz.print(config, [{ type: 'raw', format: 'plain', data: raw }]);
                await qz.websocket.disconnect();
                return true;
            } catch (e) {
                console.error('[QZ PRINT ERROR]', e);
                alert(`Gagal mencetak: ${e.message}`);
                // Jangan disconnect jika koneksi gagal, karena akan throw error lagi
                if (qz.websocket.isActive()) {
                    await qz.websocket.disconnect();
                }
                return false;
            }
        }
    };
}

/**
 * Fungsi untuk memproses pembayaran dan mengelola alur cetak.
 * @param {number|string} transactionId
 */
export async function processPayment(transactionId) {
    try {
        const { data } = await axios.patch(`/book-order/${transactionId}/pay`);

        let printed = false;
        if (data.mode === 'direct' && data.printer) {
            printed = await qzTrayPrinter().tryPrint(data.raw, data.printer);
        }

        // Jika mode PDF, atau jika cetak langsung gagal
        if (!printed) {
            Alpine.store('pdf').open(data.pdfUrl, data.redirectUrl);
        } else {
            // Jika cetak langsung berhasil, tampilkan pesan dan redirect
            alert(data.message);
            window.location.href = data.redirectUrl;
        }

    } catch (err) {
        console.error('[PAYMENT ERROR]', err.response?.data ?? err);
        const errorMessage = err.response?.data?.message || 'Gagal memproses pembayaran.';
        alert(errorMessage);
    }
}

// Attach ke window object agar bisa dipanggil dari Blade/Alpine
window.processPayment = processPayment;