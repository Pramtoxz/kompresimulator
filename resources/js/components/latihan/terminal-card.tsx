import { router } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';
import WorkspaceController from '@/actions/App/Http/Controllers/Student/WorkspaceController';
import { Button } from '@/components/ui/button';
import type { Fase } from '@/lib/fase';

type Baris = {
    id: number;
    command: string;
    output: string;
    ok: boolean;
};

type Props = {
    attemptId: number;
    total: number;
    fase: Fase;
};

export default function TerminalCard({ attemptId, total, fase }: Props) {
    const [riwayat, setRiwayat] = useState<Baris[]>([]);
    const [ketikan, setKetikan] = useState('');
    const [menunggu, setMenunggu] = useState(false);
    const bawah = useRef<HTMLDivElement>(null);

    useEffect(() => {
        return router.on('flash', (event) => {
            const hasil = (event as CustomEvent).detail?.flash?.terminal as
                | Omit<Baris, 'id'>
                | undefined;

            if (!hasil) {
                return;
            }

            setRiwayat((sebelumnya) => [
                ...sebelumnya,
                { ...hasil, id: sebelumnya.length },
            ]);
        });
    }, []);

    useEffect(() => {
        bawah.current?.scrollIntoView({ block: 'nearest' });
    }, [riwayat]);

    const jalankan = () => {
        if (ketikan.trim() === '' || menunggu) {
            return;
        }

        setMenunggu(true);
        setKetikan('');

        router.post(
            WorkspaceController.runTerminal.url(attemptId),
            { command: ketikan },
            {
                preserveScroll: true,
                preserveState: true,
                only: [],
                onFinish: () => setMenunggu(false),
            },
        );
    };

    const benar = riwayat.filter((baris) => baris.ok).length;

    return (
        <section className="space-y-2" data-tour="terminal">
            <div className="flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1">
                <h2 className="text-base font-medium">Terminal</h2>
                <p
                    className={`text-xs ${benar >= total ? fase.text : 'text-muted-foreground'}`}
                >
                    {benar >= total
                        ? 'Semua perintah sudah benar'
                        : `${benar} dari ${total} perintah benar`}
                </p>
            </div>

            <div className="overflow-hidden rounded-lg border-2">
                <div className="max-h-56 min-h-24 overflow-y-auto bg-neutral-950 p-3 font-mono text-xs leading-relaxed text-neutral-100">
                    {riwayat.length === 0 && (
                        <p className="text-neutral-500">
                            Ketik perintah dari kartu di atas, lalu tekan Enter.
                        </p>
                    )}

                    {riwayat.map((baris) => (
                        <div key={baris.id} className="mb-2">
                            <p>
                                <span className="text-neutral-500">$ </span>
                                {baris.command}
                            </p>
                            {baris.output !== '' && (
                                <p
                                    className={`whitespace-pre-wrap ${baris.ok ? 'text-emerald-300' : 'text-rose-300'}`}
                                >
                                    {baris.output}
                                </p>
                            )}
                        </div>
                    ))}

                    <div ref={bawah} />
                </div>

                <div className="bg-card flex items-center gap-2 border-t p-2">
                    <span className="text-muted-foreground pl-1 font-mono text-sm">
                        $
                    </span>
                    <input
                        value={ketikan}
                        onChange={(event) => setKetikan(event.target.value)}
                        onKeyDown={(event) => {
                            if (event.key === 'Enter') {
                                event.preventDefault();
                                jalankan();
                            }
                        }}
                        spellCheck={false}
                        autoComplete="off"
                        autoCorrect="off"
                        autoCapitalize="off"
                        placeholder="ketik perintahnya di sini"
                        aria-label="Perintah terminal"
                        className="min-w-0 flex-1 bg-transparent font-mono text-sm outline-none"
                    />
                    <Button
                        type="button"
                        size="sm"
                        onClick={jalankan}
                        disabled={menunggu || ketikan.trim() === ''}
                        className="h-9 shrink-0"
                    >
                        Jalankan
                    </Button>
                </div>
            </div>
        </section>
    );
}
