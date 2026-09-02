import { driver, type DriveStep } from 'driver.js';
import { useCallback } from 'react';
import 'driver.js/dist/driver.css';

type Anchor = {
    element: string;
    title: string;
    description: string;
};

const pembuka: Anchor[] = [
    {
        element: '[data-tour="langkah"]',
        title: 'Posisimu ada di sini',
        description:
            'Tujuh langkah dikelompokkan jadi tiga warna: pekerjaan di terminal, menulis berkas, lalu hasil di layar. Warnanya berganti begitu kamu pindah kelompok.',
    },
    {
        element: '[data-tour="instruksi"]',
        title: 'Satu layar, satu pekerjaan',
        description:
            'Yang tampil di sini cuma langkah yang sedang berjalan. Tombol Panduan di sebelah judulnya bisa kamu tekan lagi kapan saja untuk mengulang penjelasan ini.',
    },
    {
        element: '[data-tour="soal"]',
        title: 'Soalnya bisa dibuka kapan saja',
        description:
            'Tidak perlu dihafal. Tekan tombol ini setiap kali lupa nama field atau angka di tabel acuan.',
    },
];

const editor: Anchor = {
    element: '[data-tour="editor"]',
    title: 'Kotak ini bisa kamu ketik',
    description:
        'Klik di dalamnya lalu ketik seperti di editor biasa. Tekan Ctrl+S atau klik di luar kotak untuk menyimpan, dan tulisan di kanan atas berubah jadi Tersimpan.',
};

const perLangkah: Record<string, Anchor[]> = {
    install: [],
    migration: [
        editor,
        {
            element: '[data-tour="tabel"]',
            title: 'Jalankan dulu, baru lanjut',
            description:
                'Setelah migrationmu diketik, tekan tombol ini supaya tabelnya benar-benar dibuat. Hasilnya langsung tampil di bawah, jadi kamu tahu kolommu sudah benar atau belum.',
        },
    ],
    model: [editor],
    controller: [editor],
    routes: [editor],
    coding: [
        editor,
        {
            element: '[data-tour="pratinjau"]',
            title: 'Formmu hidup di sini',
            description:
                'Tiap kali kamu menyimpan, form di kotak ini ikut berubah. Coba pilih dropdown dan isi angkanya untuk memastikan hitunganmu jalan.',
        },
        {
            element: '[data-tour="data"]',
            title: 'Buktinya data tersimpan',
            description:
                'Tekan Simpan di form tadi, lalu datanya muncul di tabel ini. Kalau ada kolom kosong, periksa lagi daftar kolom di model.',
        },
        {
            element: '[data-tour="cek"]',
            title: 'Periksa pekerjaanmu',
            description:
                'Tombol ini menjalankan test case dari soal dan memberi tahu bagian mana yang belum lolos.',
        },
    ],
    done: [
        {
            element: '[data-tour="selesai"]',
            title: 'Tutup latihannya di sini',
            description:
                'Setelah semua dicoba, tandai selesai. Catatan waktumu per langkah dipakai untuk evaluasi.',
        },
    ],
};

const penutup: Anchor[] = [
    {
        element: '[data-tour="mundur"]',
        title: 'Boleh mundur kapan saja',
        description:
            'Lupa isi langkah sebelumnya? Kembali saja. Pekerjaanmu tidak hilang dan catatan waktunya tidak berubah.',
    },
    {
        element: '[data-tour="lanjut"]',
        title: 'Kalau sudah, lanjut',
        description:
            'Tekan ini untuk pindah ke langkah berikutnya. Kamu selalu bisa balik lagi.',
    },
];

function build(stepKey: string): DriveStep[] {
    return [...pembuka, ...(perLangkah[stepKey] ?? [editor]), ...penutup].map(
        ({ element, title, description }) => ({
            element,
            popover: { title, description },
        }),
    );
}

function seen(key: string): boolean {
    try {
        return window.localStorage.getItem(key) !== null;
    } catch {
        return true;
    }
}

function remember(key: string): void {
    try {
        window.localStorage.setItem(key, 'sudah');
    } catch {
        return;
    }
}

export default function useTour() {
    return useCallback((stepKey: string, force = false) => {
        const key = 'tur-latihan';

        if (!force && seen(key)) {
            return;
        }

        remember(key);

        driver({
            steps: build(stepKey),
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
