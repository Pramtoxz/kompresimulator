import { forwardRef, useEffect, useImperativeHandle, useRef } from 'react';
import type { TestCaseInput } from '@/types/latihan';

const bridge = `
<script>
function readTotal(field) {
    var element = document.querySelector('[name="' + field + '"]') || document.getElementById(field);
    if (!element) { return null; }
    var raw = element.value !== undefined && element.value !== null ? element.value : element.textContent;
    var number = parseFloat(String(raw).replace(/[^0-9.,-]/g, '').replace(/\.(?=\d{3}\b)/g, '').replace(',', '.'));
    return isNaN(number) ? null : number;
}

document.addEventListener('submit', function (event) {
    event.preventDefault();
    var data = {};
    new FormData(event.target).forEach(function (value, key) { data[key] = value; });
    parent.postMessage({ source: 'kompre-preview', type: 'submit', data: data }, '*');
});

window.addEventListener('message', function (event) {
    var message = event.data;
    if (!message || message.target !== 'kompre-preview' || message.command !== 'run-case') { return; }

    message.inputs.forEach(function (input) {
        var element = document.querySelector('[name="' + input.field + '"]') || document.getElementById(input.field);
        if (!element) { return; }
        element.value = input.value;
        element.dispatchEvent(new Event('input', { bubbles: true }));
        element.dispatchEvent(new Event('change', { bubbles: true }));
        element.dispatchEvent(new Event('keyup', { bubbles: true }));
    });

    setTimeout(function () {
        parent.postMessage({
            source: 'kompre-preview',
            type: 'case-result',
            caseId: message.caseId,
            total: readTotal(message.totalField),
        }, '*');
    }, 60);
});
<\/script>
`;

const style = `
<style>
    body { font-family: system-ui, sans-serif; margin: 0; padding: 16px; color: #16171b; background: #fff; }
    input, select, textarea, button { font: inherit; }
</style>
`;

export type PreviewHandle = {
    runCase: (
        caseId: number,
        inputs: TestCaseInput[],
        totalField: string,
    ) => Promise<number | null>;
};

type Props = {
    html: string;
    onSubmit: (data: Record<string, string>) => void;
};

const PreviewFrame = forwardRef<PreviewHandle, Props>(function PreviewFrame(
    { html, onSubmit },
    ref,
) {
    const frame = useRef<HTMLIFrameElement>(null);

    useEffect(() => {
        const listener = (event: MessageEvent) => {
            if (event.data?.source !== 'kompre-preview') {
                return;
            }

            if (event.data.type === 'submit') {
                onSubmit(event.data.data as Record<string, string>);
            }
        };

        window.addEventListener('message', listener);

        return () => window.removeEventListener('message', listener);
    }, [onSubmit]);

    useImperativeHandle(ref, () => ({
        runCase: (caseId, inputs, totalField) =>
            new Promise((resolve) => {
                const target = frame.current?.contentWindow;

                if (!target) {
                    resolve(null);

                    return;
                }

                const timer = window.setTimeout(() => {
                    window.removeEventListener('message', listener);
                    resolve(null);
                }, 2000);

                const listener = (event: MessageEvent) => {
                    if (
                        event.data?.source !== 'kompre-preview' ||
                        event.data.type !== 'case-result' ||
                        event.data.caseId !== caseId
                    ) {
                        return;
                    }

                    window.clearTimeout(timer);
                    window.removeEventListener('message', listener);
                    resolve(event.data.total as number | null);
                };

                window.addEventListener('message', listener);
                target.postMessage(
                    {
                        target: 'kompre-preview',
                        command: 'run-case',
                        caseId,
                        inputs,
                        totalField,
                    },
                    '*',
                );
            }),
    }));

    if (html.trim() === '') {
        return (
            <div className="text-muted-foreground bg-muted/30 flex h-[26rem] items-center justify-center rounded-lg border px-6 text-center text-sm">
                Tulis form di berkas tampilan, hasilnya muncul di sini.
            </div>
        );
    }

    return (
        <iframe
            ref={frame}
            title="Pratinjau"
            sandbox="allow-scripts allow-forms"
            srcDoc={`<!doctype html><html lang="id"><head><meta charset="utf-8">${style}</head><body>${html}${bridge}</body></html>`}
            className="h-[26rem] w-full rounded-lg border bg-white"
        />
    );
});

export default PreviewFrame;
