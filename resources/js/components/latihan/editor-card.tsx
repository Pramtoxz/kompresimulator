import CodeEditor from './code-editor';

type Props = {
    path: string;
    content: string;
    onSave: (content: string) => void;
};

export default function EditorCard({ path, content, onSave }: Props) {
    return (
        <section className="space-y-2">
            <div className="flex items-baseline justify-between gap-3">
                <p className="font-mono text-xs break-all">{path}</p>
                <p className="text-muted-foreground shrink-0 text-xs">
                    tersimpan saat klik di luar
                </p>
            </div>

            <CodeEditor key={path} value={content} onSave={onSave} />
        </section>
    );
}
