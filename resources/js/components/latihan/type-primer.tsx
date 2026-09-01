import type { BriefingColumn } from '@/types/latihan';

export default function TypePrimer({ columns }: { columns: BriefingColumn[] }) {
    if (columns.length === 0) {
        return null;
    }

    return (
        <ul className="space-y-2">
            {columns.map((column) => (
                <li key={column.name} className="rounded-lg border px-3 py-2.5">
                    <div className="flex flex-wrap items-baseline gap-x-2 gap-y-1">
                        <span className="text-sm font-medium">
                            {column.label}
                        </span>
                        <span className="text-muted-foreground font-mono text-xs">
                            {column.name}
                        </span>
                        <span className="bg-muted ml-auto rounded px-1.5 py-0.5 font-mono text-xs">
                            {column.sql}
                        </span>
                    </div>

                    {column.reason && (
                        <p className="text-muted-foreground mt-1.5 text-sm leading-relaxed">
                            {column.reason}
                        </p>
                    )}
                </li>
            ))}
        </ul>
    );
}
