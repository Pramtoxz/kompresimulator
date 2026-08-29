import { useEffect, useRef, useState } from 'react';

type Props = {
    value: string;
    onSave: (content: string) => void;
};

export default function CodeEditor({ value, onSave }: Props) {
    const [content, setContent] = useState(value);
    const textarea = useRef<HTMLTextAreaElement>(null);

    useEffect(() => {
        setContent(value);
    }, [value]);

    const handleTab = (event: React.KeyboardEvent<HTMLTextAreaElement>) => {
        if (event.key !== 'Tab') {
            return;
        }

        event.preventDefault();

        const element = event.currentTarget;
        const start = element.selectionStart;
        const end = element.selectionEnd;
        const next = `${content.slice(0, start)}    ${content.slice(end)}`;

        setContent(next);

        window.requestAnimationFrame(() => {
            element.selectionStart = start + 4;
            element.selectionEnd = start + 4;
        });
    };

    return (
        <textarea
            ref={textarea}
            value={content}
            onChange={(event) => setContent(event.target.value)}
            onBlur={() => onSave(content)}
            onKeyDown={handleTab}
            spellCheck={false}
            autoComplete="off"
            autoCorrect="off"
            autoCapitalize="off"
            data-gramm="false"
            aria-label="Editor kode"
            className="bg-muted/40 h-[26rem] w-full resize-none rounded-lg border p-3 font-mono text-[13px] leading-relaxed focus-visible:ring-1 focus-visible:outline-none"
        />
    );
}
