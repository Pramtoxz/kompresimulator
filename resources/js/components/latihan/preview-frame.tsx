import { useEffect } from 'react';

const bridge = `
<script>
document.addEventListener('submit', function (event) {
    event.preventDefault();
    var data = {};
    new FormData(event.target).forEach(function (value, key) { data[key] = value; });
    parent.postMessage({ source: 'kompre-preview', data: data }, '*');
});
<\/script>
`;

const style = `
<style>
    body { font-family: system-ui, sans-serif; margin: 0; padding: 16px; color: #16171b; background: #fff; }
    input, select, textarea, button { font: inherit; }
</style>
`;

type Props = {
    html: string;
    onSubmit: (data: Record<string, string>) => void;
};

export default function PreviewFrame({ html, onSubmit }: Props) {
    useEffect(() => {
        const listener = (event: MessageEvent) => {
            if (event.data?.source !== 'kompre-preview') {
                return;
            }

            onSubmit(event.data.data as Record<string, string>);
        };

        window.addEventListener('message', listener);

        return () => window.removeEventListener('message', listener);
    }, [onSubmit]);

    if (html.trim() === '') {
        return (
            <div className="text-muted-foreground bg-muted/30 flex h-[26rem] items-center justify-center rounded-lg border px-6 text-center text-sm">
                Tulis form di berkas tampilan, hasilnya muncul di sini.
            </div>
        );
    }

    return (
        <iframe
            title="Pratinjau"
            sandbox="allow-scripts allow-forms"
            srcDoc={`<!doctype html><html lang="id"><head><meta charset="utf-8">${style}</head><body>${html}${bridge}</body></html>`}
            className="h-[26rem] w-full rounded-lg border bg-white"
        />
    );
}
