import { Badge } from '@/components/ui/badge';
import {
    DataTable,
    EmptyRow,
    TableBody,
    TableCell,
    TableHead,
    TableRow,
} from '@/components/admin/data-table';
import type { PracticeHistoryRow } from '@/types/latihan';

export default function HistoryTable({
    rows,
}: {
    rows: PracticeHistoryRow[];
}) {
    return (
        <DataTable>
            <TableHead columns={['Soal', 'Level', 'Durasi', 'Hasil', 'Selesai']} />
            <TableBody>
                {rows.length === 0 && (
                    <EmptyRow
                        colSpan={5}
                        message="Belum ada latihan yang selesai."
                    />
                )}

                {rows.map((row) => (
                    <TableRow key={row.id}>
                        <TableCell className="max-w-sm">
                            {row.title ?? '—'}
                        </TableCell>
                        <TableCell className="whitespace-nowrap">
                            {row.level_label}
                        </TableCell>
                        <TableCell className="whitespace-nowrap">
                            {row.duration_minutes ?? '—'} / {row.target_minutes}{' '}
                            mnt
                        </TableCell>
                        <TableCell>
                            <Badge
                                variant={
                                    row.within_target
                                        ? 'default'
                                        : 'destructive'
                                }
                            >
                                {row.within_target
                                    ? 'Masuk target'
                                    : 'Lewat target'}
                            </Badge>
                        </TableCell>
                        <TableCell className="whitespace-nowrap">
                            {row.finished_at}
                        </TableCell>
                    </TableRow>
                ))}
            </TableBody>
        </DataTable>
    );
}
