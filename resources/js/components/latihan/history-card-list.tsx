import { Badge } from '@/components/ui/badge';
import type { PracticeHistoryRow } from '@/types/latihan';

export default function HistoryCardList({
    rows,
}: {
    rows: PracticeHistoryRow[];
}) {
    if (rows.length === 0) {
        return (
            <p className="text-muted-foreground border-sidebar-border/70 dark:border-sidebar-border rounded-xl border px-4 py-10 text-center text-sm">
                Belum ada latihan yang selesai.
            </p>
        );
    }

    return (
        <ul className="space-y-3">
            {rows.map((row) => (
                <li
                    key={row.id}
                    className="border-sidebar-border/70 dark:border-sidebar-border space-y-2 rounded-xl border p-4"
                >
                    <div className="flex items-start justify-between gap-3">
                        <p className="text-sm font-medium">
                            {row.title ?? '—'}
                        </p>
                        <Badge
                            variant={row.within_target ? 'default' : 'destructive'}
                        >
                            {row.within_target ? 'Masuk target' : 'Lewat target'}
                        </Badge>
                    </div>

                    <p className="font-mono text-2xl tabular-nums">
                        {row.duration_minutes ?? '—'}
                        <span className="text-muted-foreground ml-1 font-sans text-xs">
                            / {row.target_minutes} menit
                        </span>
                    </p>

                    <p className="text-muted-foreground text-xs">
                        {row.level_label} · {row.finished_at}
                    </p>
                </li>
            ))}
        </ul>
    );
}
