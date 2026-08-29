import type { ReactNode } from 'react';

export function DataTable({ children }: { children: ReactNode }) {
    return (
        <div className="border-sidebar-border/70 dark:border-sidebar-border overflow-x-auto rounded-xl border">
            <table className="w-full caption-bottom text-sm">{children}</table>
        </div>
    );
}

export function TableHead({ columns }: { columns: string[] }) {
    return (
        <thead className="bg-muted/50">
            <tr>
                {columns.map((column) => (
                    <th
                        key={column}
                        className="text-muted-foreground px-4 py-3 text-left font-medium whitespace-nowrap"
                    >
                        {column}
                    </th>
                ))}
            </tr>
        </thead>
    );
}

export function TableBody({ children }: { children: ReactNode }) {
    return <tbody className="divide-border divide-y">{children}</tbody>;
}

export function TableRow({ children }: { children: ReactNode }) {
    return <tr className="hover:bg-muted/40 transition-colors">{children}</tr>;
}

export function TableCell({
    children,
    className = '',
}: {
    children: ReactNode;
    className?: string;
}) {
    return <td className={`px-4 py-3 align-top ${className}`}>{children}</td>;
}

export function EmptyRow({
    colSpan,
    message,
}: {
    colSpan: number;
    message: string;
}) {
    return (
        <tr>
            <td
                colSpan={colSpan}
                className="text-muted-foreground px-4 py-10 text-center"
            >
                {message}
            </td>
        </tr>
    );
}
