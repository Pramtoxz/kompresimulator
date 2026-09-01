import type { FormField } from '@/types/latihan';

const hints: Record<string, string> = {
    select: 'select box',
    readonly: 'otomatis',
    number: 'angka',
    date: 'tanggal',
};

export default function FormMockup({ fields }: { fields: FormField[] }) {
    return (
        <div className="space-y-3">
            {fields.map((field) => (
                <div
                    key={field.name}
                    className="grid items-center gap-2 sm:grid-cols-[11rem_1fr]"
                >
                    <span className="text-sm font-medium">{field.label}</span>

                    <span
                        className={`flex h-9 items-center justify-between rounded-md border px-3 text-xs ${
                            field.input === 'readonly'
                                ? 'bg-muted/60 text-muted-foreground'
                                : 'bg-background text-muted-foreground'
                        }`}
                    >
                        <span className="font-mono">{field.name}</span>
                        {hints[field.input] && <span>{hints[field.input]}</span>}
                    </span>
                </div>
            ))}

            <div className="flex gap-2 pt-1 sm:pl-[11.5rem]">
                <span className="rounded-md border px-4 py-1.5 text-xs font-medium">
                    Simpan
                </span>
                <span className="rounded-md border px-4 py-1.5 text-xs font-medium">
                    Laporan
                </span>
            </div>
        </div>
    );
}
