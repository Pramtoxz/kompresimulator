import type { LookupTable as Lookup } from '@/types/latihan';

export default function LookupTable({ lookup }: { lookup: Lookup }) {
    if (lookup.columns.length === 0 || lookup.rows.length === 0) {
        return null;
    }

    return (
        <div className="overflow-x-auto rounded-lg border">
            <table className="w-full text-left text-sm">
                <thead className="bg-muted/50">
                    <tr>
                        {lookup.columns.map((column) => (
                            <th
                                key={column}
                                className="px-3 py-2 font-medium whitespace-nowrap"
                            >
                                {column}
                            </th>
                        ))}
                    </tr>
                </thead>
                <tbody className="divide-border divide-y">
                    {lookup.rows.map((row, index) => (
                        <tr key={index}>
                            {row.map((cell, cellIndex) => (
                                <td
                                    key={cellIndex}
                                    className="px-3 py-2 whitespace-nowrap"
                                >
                                    {cell}
                                </td>
                            ))}
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}
