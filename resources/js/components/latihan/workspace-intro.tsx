import { Card, CardContent } from '@/components/ui/card';

const steps = [
    'Baca soal di bawah, lalu ikuti panduan langkah yang sedang menyala.',
    'Ketik kode di Editor. Pindah berkas lewat tab di atas editor. Kode tersimpan sendiri saat kamu klik di luar.',
    'Tekan Jalankan migration setelah berkas migration selesai, supaya tabelnya benar-benar dibuat.',
    'Isi form di Pratinjau untuk menguji hasilmu. Data yang masuk muncul di Tabel latihan.',
    'Kalau satu langkah beres, tekan Langkah selesai di bawah.',
];

export default function WorkspaceIntro() {
    return (
        <Card className="bg-muted/30">
            <CardContent className="space-y-2">
                <p className="text-sm font-medium">Cara kerja halaman ini</p>
                <ol className="text-muted-foreground list-inside list-decimal space-y-1 text-sm">
                    {steps.map((step) => (
                        <li key={step}>{step}</li>
                    ))}
                </ol>
            </CardContent>
        </Card>
    );
}
