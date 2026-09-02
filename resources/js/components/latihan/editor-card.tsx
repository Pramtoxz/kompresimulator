import { useEffect, useState } from 'react';
import type { Fase } from '@/lib/fase';
import CodeEditor from './code-editor';

type Status = 'idle' | 'dirty' | 'saved';

const messages: Record<Status, string> = {
    idle: 'Klik lalu ketik. Tersimpan sendiri saat kamu klik di luar.',
    dirty: 'Ada perubahan yang belum tersimpan',
    saved: 'Tersimpan',
};

type Props = {
    path: string;
    content: string;
    onSave: (content: string) => void;
    fase: Fase;
};

export default function EditorCard({ path, content, onSave, fase }: Props) {
    const [status, setStatus] = useState<Status>('idle');

    useEffect(() => {
        setStatus('idle');
    }, [path]);

    useEffect(() => {
        if (status !== 'saved') {
            return;
        }

        const timer = window.setTimeout(() => setStatus('idle'), 2500);

        return () => window.clearTimeout(timer);
    }, [status]);

    const save = (next: string) => {
        onSave(next);
        setStatus('saved');
    };

    return (
        <section className="space-y-2" data-tour="editor">
            <div className="flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1">
                <p className="font-mono text-xs break-all">{path}</p>

                <p
                    aria-live="polite"
                    className={`shrink-0 text-xs ${
                        status === 'dirty'
                            ? 'text-destructive'
                            : status === 'saved'
                              ? fase.text
                              : 'text-muted-foreground'
                    }`}
                >
                    {messages[status]}
                </p>
            </div>

            <CodeEditor
                key={path}
                value={content}
                onSave={save}
                onDirty={() => setStatus('dirty')}
                accent={fase.focusEdge}
            />
        </section>
    );
}
