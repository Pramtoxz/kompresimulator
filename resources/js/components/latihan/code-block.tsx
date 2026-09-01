const labels: Record<string, string> = {
    bash: 'ketik di terminal',
    ini: 'isi file .env',
    sql: 'jalankan di phpMyAdmin',
    php: 'tulis di file',
    blade: 'tulis di file',
    html: 'tulis di file',
    javascript: 'tulis di file',
};

type Props = {
    code: string;
    language: string;
};

export default function CodeBlock({ code, language }: Props) {
    return (
        <div className="overflow-hidden rounded-lg border">
            <p className="text-muted-foreground bg-muted/60 border-b px-3 py-1.5 text-[11px] tracking-wide uppercase">
                {labels[language] ?? language}
            </p>
            <pre className="bg-muted/30 max-h-80 overflow-auto p-3 font-mono text-xs leading-relaxed">
                {code}
            </pre>
        </div>
    );
}
