import { driver } from 'driver.js';
import { useCallback } from 'react';
import 'driver.js/dist/driver.css';

const steps = [
    {
        element: '[data-tour="langkah"]',
        popover: {
            title: 'Posisimu ada di sini',
            description:
                'Tujuh langkah dikelompokkan jadi tiga warna: pekerjaan di terminal, menulis berkas, lalu hasil di layar. Warnanya berganti begitu kamu pindah kelompok.',
        },
    },
    {
        element: '[data-tour="soal"]',
        popover: {
            title: 'Soalnya bisa dibuka kapan saja',
            description:
                'Tidak perlu dihafal. Tekan tombol ini setiap kali kamu lupa nama field atau angka di tabel acuan.',
        },
    },
    {
        element: '[data-tour="instruksi"]',
        popover: {
            title: 'Satu layar, satu pekerjaan',
            description:
                'Bacaan di sini cuma untuk langkah yang sedang berjalan, lengkap dengan kode yang bisa kamu tiru.',
        },
    },
    {
        element: '[data-tour="editor"]',
        popover: {
            title: 'Kotak ini bisa kamu ketik',
            description:
                'Klik di dalamnya lalu ketik seperti di editor biasa. Tekan Ctrl+S atau klik di luar kotak untuk menyimpan, dan tulisan di kanan atas akan berubah jadi Tersimpan.',
        },
    },
    {
        element: '[data-tour="mundur"]',
        popover: {
            title: 'Boleh mundur kapan saja',
            description:
                'Lupa isi langkah sebelumnya? Kembali saja. Pekerjaanmu tidak hilang dan catatan waktunya tidak berubah.',
        },
    },
    {
        element: '[data-tour="lanjut"]',
        popover: {
            title: 'Kalau sudah, lanjut',
            description:
                'Tekan ini untuk pindah ke langkah berikutnya. Kamu selalu bisa balik lagi.',
        },
    },
];

export default function useTour() {
    return useCallback((storageKey: string) => {
        try {
            if (window.localStorage.getItem(storageKey) !== null) {
                return;
            }
        } catch {
            return;
        }

        try {
            window.localStorage.setItem(storageKey, 'sudah');
        } catch {
            return;
        }

        driver({
            steps,
            showProgress: true,
            skipMissingElement: true,
            allowClose: true,
            progressText: 'Bagian {{current}} dari {{total}}',
            nextBtnText: 'Lanjut',
            prevBtnText: 'Kembali',
            doneBtnText: 'Mengerti',
            overlayOpacity: 0.65,
            stagePadding: 6,
        }).drive();
    }, []);
}
