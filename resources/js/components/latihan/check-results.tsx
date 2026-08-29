import { Badge } from '@/components/ui/badge';
import type { WorkspaceCheck } from '@/types/latihan';

const labels: Record<string, string> = {
    structure: 'Struktur kode',
    db: 'Tabel database',
    calc: 'Kalkulasi',
};

export default function CheckResults({ checks }: { checks: WorkspaceCheck[] }) {
    if (checks.length === 0) {
        return (
            <p className="text-muted-foreground bg-muted/30 rounded-lg border px-4 py-6 text-center text-sm">
                Belum dicek. Tekan Cek pekerjaan untuk menilai hasilmu.
            </p>
        );
    }

    return (
        <ul className="space-y-2">
            {checks.map((check, index) => (
                <li
                    key={index}
                    className="flex items-start justify-between gap-3 rounded-lg border px-3 py-2.5"
                >
                    <div className="min-w-0 space-y-0.5">
                        <p className="text-muted-foreground text-xs">
                            {labels[check.kind] ?? check.kind}
                        </p>
                        <p className="text-sm">{check.message}</p>
                    </div>
                    <Badge variant={check.passed ? 'default' : 'destructive'}>
                        {check.passed ? 'Lolos' : 'Belum'}
                    </Badge>
                </li>
            ))}
        </ul>
    );
}
