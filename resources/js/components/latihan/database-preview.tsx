import type { WorkspaceDatabase } from '@/types/latihan';

export default function DatabasePreview({
    database,
}: {
    database: WorkspaceDatabase;
}) {
    if (database.table === null) {
        return (
            <p className="text-muted-foreground bg-muted/30 rounded-lg border px-4 py-6 text-center text-sm">
                Tabel belum dibuat. Tulis migration lalu tekan Jalankan
                migration.
            </p>
        );
    }

    return (
        <div className="space-y-2">
            <p className="text-muted-foreground font-mono text-xs">
                latihan.{database.table}
            </p>

            <div className="overflow-x-auto rounded-lg border">
                <table className="w-full text-left text-xs">
                    <thead className="bg-muted/50">
                        <tr>
                            {database.columns.map((column) => (
                                <th
                                    key={column}
                                    className="px-3 py-2 font-mono font-medium whitespace-nowrap"
                                >
                                    {column}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody className="divide-border divide-y">
                        {database.rows.length === 0 && (
                            <tr>
                                <td
                                    colSpan={database.columns.length}
                                    className="text-muted-foreground px-3 py-6 text-center"
                                >
                                    Belum ada data. Isi form di pratinjau lalu
                                    kirim.
                                </td>
                            </tr>
                        )}

                        {database.rows.map((row, index) => (
                            <tr key={index}>
                                {database.columns.map((column) => (
                                    <td
                                        key={column}
                                        className="px-3 py-2 font-mono whitespace-nowrap"
                                    >
                                        {String(row[column] ?? '')}
                                    </td>
                                ))}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
