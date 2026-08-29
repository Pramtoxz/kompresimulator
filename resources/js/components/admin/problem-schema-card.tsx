import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { ProblemColumn } from '@/types/admin';

type Props = {
    table?: string;
    columns?: ProblemColumn[];
};

export default function ProblemSchemaCard({ table, columns = [] }: Props) {
    return (
        <Card>
            <CardHeader>
                <CardTitle>Tabel: {table ?? '—'}</CardTitle>
            </CardHeader>
            <CardContent>
                <ul className="divide-border divide-y text-sm">
                    {columns.map((column) => (
                        <li
                            key={column.name}
                            className="flex items-center justify-between gap-4 py-2"
                        >
                            <span className="font-mono">{column.name}</span>
                            <span className="text-muted-foreground">
                                {column.type}
                                {column.nullable ? ' · nullable' : ''}
                            </span>
                        </li>
                    ))}
                </ul>
            </CardContent>
        </Card>
    );
}
