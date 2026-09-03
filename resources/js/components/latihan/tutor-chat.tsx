import { router } from '@inertiajs/react';
import { MessageCircle } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import TutorController from '@/actions/App/Http/Controllers/Student/TutorController';
import LottieArt from '@/components/lottie-art';
import { Button } from '@/components/ui/button';
import { aset } from '@/lib/aset';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';

type Pesan = {
    id: number;
    role: 'student' | 'assistant';
    body: string;
    refused: boolean;
};

const pembuka: Pesan = {
    id: 0,
    role: 'assistant',
    body: 'Halo, Bg Dito di sini. Tanya apa saja soal PHP, Laravel, CodeIgniter, atau langkah latihan yang bikin bingung. Tapi jawaban soalmu tidak akan Bg kasih ya, itu bagianmu.',
    refused: false,
};

export default function TutorChat({
    attemptId = null,
    diAtasDock = false,
}: {
    attemptId?: number | null;
    diAtasDock?: boolean;
}) {
    const [pesan, setPesan] = useState<Pesan[]>([pembuka]);
    const [ketikan, setKetikan] = useState('');
    const [menunggu, setMenunggu] = useState(false);
    const bawah = useRef<HTMLDivElement>(null);

    useEffect(() => {
        return router.on('flash', (event) => {
            const balasan = (event as CustomEvent).detail?.flash?.tutor as
                | { body: string; refused: boolean }
                | undefined;

            if (!balasan) {
                return;
            }

            setPesan((sebelumnya) => [
                ...sebelumnya,
                {
                    id: sebelumnya.length,
                    role: 'assistant',
                    body: balasan.body,
                    refused: balasan.refused,
                },
            ]);
        });
    }, []);

    useEffect(() => {
        bawah.current?.scrollIntoView({ block: 'nearest' });
    }, [pesan]);

    const kirim = () => {
        const isi = ketikan.trim();

        if (isi === '' || menunggu) {
            return;
        }

        setPesan((sebelumnya) => [
            ...sebelumnya,
            {
                id: sebelumnya.length,
                role: 'student',
                body: isi,
                refused: false,
            },
        ]);
        setKetikan('');
        setMenunggu(true);

        router.post(
            attemptId === null
                ? TutorController.general.url()
                : TutorController.store.url(attemptId),
            { question: isi },
            {
                preserveScroll: true,
                preserveState: true,
                only: [],
                onFinish: () => setMenunggu(false),
            },
        );
    };

    return (
        <Sheet>
            <SheetTrigger asChild>
                <Button
                    size="icon"
                    className={`safe-b fixed right-4 z-40 size-14 overflow-hidden rounded-full shadow-lg sm:right-6 ${
                        diAtasDock ? 'bottom-24' : 'bottom-6'
                    }`}
                    aria-label="Tanya Bg Dito Ganteng"
                    data-tour="tanya"
                >
                    <MessageCircle className="size-6" />
                    <LottieArt
                        {...aset.asisten}
                        className="pointer-events-none absolute inset-0 size-full"
                    />
                </Button>
            </SheetTrigger>

            <SheetContent
                side="right"
                className="flex w-full flex-col gap-0 p-0 sm:max-w-md"
            >
                <SheetHeader className="border-b">
                    <SheetTitle>Bg Dito Ganteng</SheetTitle>
                    <SheetDescription>
                        Menjelaskan konsep, bukan memberi jawaban.
                    </SheetDescription>
                </SheetHeader>

                <div className="flex-1 space-y-3 overflow-y-auto p-4">
                    {pesan.map((item) => (
                        <div
                            key={item.id}
                            className={
                                item.role === 'student'
                                    ? 'flex justify-end'
                                    : 'flex justify-start'
                            }
                        >
                            <p
                                className={`max-w-[85%] rounded-2xl px-3 py-2 text-sm leading-relaxed ${
                                    item.role === 'student'
                                        ? 'bg-primary text-primary-foreground'
                                        : item.refused
                                          ? 'bg-muted text-muted-foreground'
                                          : 'bg-muted'
                                }`}
                            >
                                {item.body}
                            </p>
                        </div>
                    ))}

                    {menunggu && (
                        <div className="flex items-center gap-2">
                            <LottieArt
                                {...aset.asisten}
                                alt="Bg Dito sedang berpikir"
                                className="size-8 shrink-0"
                            />
                            <p className="text-muted-foreground text-sm">
                                Bg Dito lagi mikir...
                            </p>
                        </div>
                    )}

                    <div ref={bawah} />
                </div>

                <div className="safe-b flex items-end gap-2 border-t p-3">
                    <textarea
                        value={ketikan}
                        onChange={(event) => setKetikan(event.target.value)}
                        onKeyDown={(event) => {
                            if (event.key === 'Enter' && !event.shiftKey) {
                                event.preventDefault();
                                kirim();
                            }
                        }}
                        rows={2}
                        maxLength={500}
                        placeholder="Tanya apa yang bikin bingung"
                        aria-label="Pertanyaan untuk Bg Dito"
                        className="bg-card min-w-0 flex-1 resize-none rounded-lg border p-2 text-sm outline-none"
                    />
                    <Button
                        type="button"
                        onClick={kirim}
                        disabled={menunggu || ketikan.trim() === ''}
                        className="h-10 shrink-0"
                    >
                        Kirim
                    </Button>
                </div>
            </SheetContent>
        </Sheet>
    );
}
