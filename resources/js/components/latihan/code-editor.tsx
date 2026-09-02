import { useEffect, useRef, useState } from 'react';

type Props = {
    value: string;
    onSave: (content: string) => void;
    onDirty: () => void;
    accent: string;
};

export default function CodeEditor({ value, onSave, onDirty, accent }: Props) {
    const [content, setContent] = useState(value);
    const textarea = useRef<HTMLTextAreaElement>(null);

    useEffect(() => {
        setContent(value);
    }, [value]);

    const change = (next: string) => {
        setContent(next);
        onDirty();
    };

    const handleKey = (event: React.KeyboardEvent<HTMLTextAreaElement>) => {
        if ((event.ctrlKey || event.metaKey) && event.key === 's') {
            event.preventDefault();
            onSave(content);

            return;
        }

        if (event.key !== 'Tab') {
            return;
        }

        event.preventDefault();

        const element = event.currentTarget;
        const start = element.selectionStart;
        const end = element.selectionEnd;
        const next = `${content.slice(0, start)}    ${content.slice(end)}`;

        change(next);

        window.requestAnimationFrame(() => {
            element.selectionStart = start + 4;
            element.selectionEnd = start + 4;
        });
    };

    return (
        <textarea
            ref={textarea}
            value={content}
            onChange={(event) => change(event.target.value)}
            onBlur={() => onSave(content)}
            onKeyDown={handleKey}
            spellCheck={false}
            autoComplete="off"
            autoCorrect="off"
            autoCapitalize="off"
            data-gramm="false"
            placeholder="Ketik kodemu di sini"
            aria-label="Editor kode"
            className={`bg-card placeholder:text-muted-foreground/70 h-[26rem] w-full resize-none rounded-lg border-2 p-3 font-mono text-[13px] leading-relaxed shadow-inner transition-colors outline-none placeholder:font-sans ${accent}`}
        />
    );
}
