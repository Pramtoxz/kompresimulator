import { useEffect, useState } from 'react';

function format(seconds: number): string {
    const safe = Math.abs(seconds);
    const minutes = Math.floor(safe / 60);

    return `${String(minutes).padStart(2, '0')}:${String(safe % 60).padStart(2, '0')}`;
}

type Props = {
    startedAt: string;
    targetMinutes: number;
};

export default function ExamTimer({ startedAt, targetMinutes }: Props) {
    const [elapsed, setElapsed] = useState(0);

    useEffect(() => {
        const start = new Date(startedAt).getTime();
        const tick = () =>
            setElapsed(Math.max(0, Math.floor((Date.now() - start) / 1000)));

        tick();
        const timer = window.setInterval(tick, 1000);

        return () => window.clearInterval(timer);
    }, [startedAt]);

    const remaining = targetMinutes * 60 - elapsed;
    const over = remaining < 0;

    return (
        <header className="border-sidebar-border/60 bg-background/95 safe-x safe-t sticky top-0 z-30 border-b backdrop-blur">
            <div className="mx-auto flex w-full max-w-3xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
                <div className="min-w-0">
                    <p className="text-muted-foreground text-xs">
                        {over ? 'Lewat dari waktu' : 'Sisa waktu'}
                    </p>
                    <p
                        className={`font-mono text-3xl leading-tight tabular-nums ${over ? 'text-destructive' : ''}`}
                        aria-live="off"
                    >
                        {over ? '+' : ''}
                        {format(remaining)}
                    </p>
                </div>

                <p className="text-muted-foreground shrink-0 text-right text-xs">
                    Target {targetMinutes} menit
                    <br />
                    Tekan Selesai kalau sudah
                </p>
            </div>
        </header>
    );
}
