import { useEffect, useState } from 'react';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import useNarration from '@/hooks/use-narration';
import type { Briefing } from '@/types/latihan';
import TypePrimer from './type-primer';

type Props = {
    briefing: Briefing;
    audio: string[];
    attemptId: number;
};

export default function BriefingDialog({
    briefing,
    audio,
    attemptId,
}: Props) {
    const [open, setOpen] = useState(false);
    const narration = useNarration();
    const storageKey = `briefing-${attemptId}`;

    useEffect(() => {
        try {
            if (window.localStorage.getItem(storageKey) === null) {
                setOpen(true);
            }
        } catch {
            setOpen(true);
        }
    }, [storageKey]);

    const understand = () => {
        narration.stop();

        try {
            window.localStorage.setItem(storageKey, 'paham');
        } catch {
            setOpen(false);
        }

        setOpen(false);
    };

    const change = (next: boolean) => {
        if (! next) {
            narration.stop();
        }

        setOpen(next);
    };

    return (
        <>
            <Button
                variant="ghost"
                size="sm"
                onClick={() => setOpen(true)}
                className="text-muted-foreground h-9 px-0 text-xs"
            >
                Baca ulang penjelasan awal
            </Button>

            <Dialog open={open} onOpenChange={change}>
                <DialogContent className="max-h-[85svh] overflow-y-auto sm:max-w-lg">
                    <DialogHeader>
                        <DialogTitle>Kita bedah dulu soalnya</DialogTitle>
                        <DialogDescription>
                            Baca sebentar sebelum mulai. Setelah paham, tujuh
                            langkahnya tinggal diikuti satu per satu.
                        </DialogDescription>
                    </DialogHeader>

                    {audio.length > 0 && (
                        <Button
                            variant="outline"
                            onClick={() =>
                                narration.playing
                                    ? narration.stop()
                                    : narration.play(audio)
                            }
                            className="h-11 w-full"
                        >
                            {narration.playing
                                ? 'Hentikan suara'
                                : 'Dengarkan penjelasannya'}
                        </Button>
                    )}

                    <div className="space-y-5">
                        <section className="space-y-2">
                            <h3 className="text-sm font-medium">
                                {briefing.title ?? 'Studi kasus'}
                            </h3>
                            {briefing.brief && (
                                <p className="text-muted-foreground text-sm leading-relaxed">
                                    {briefing.brief}
                                </p>
                            )}
                            <p className="text-sm leading-relaxed">
                                Semua data itu nanti masuk ke satu tabel bernama{' '}
                                <span className="font-mono text-xs">
                                    {briefing.table}
                                </span>
                                .
                                {briefing.key_field_label &&
                                    ` ${briefing.key_field_label} berbentuk dropdown, dan memilihnya mengisi field lain secara otomatis.`}
                                {briefing.total_field_label &&
                                    ` ${briefing.total_field_label} tidak diketik manual, tapi dihitung sendiri.`}
                            </p>
                        </section>

                        <section className="space-y-2">
                            <h3 className="text-sm font-medium">
                                Tiap kolom punya tipe, dan tipenya tidak asal
                                pilih
                            </h3>
                            <p className="text-muted-foreground text-sm leading-relaxed">
                                Nama orang pakai varchar, uang dan jumlah pakai
                                int, tanggal pakai date. Inilah daftar kolom
                                soalmu beserta tipenya.
                            </p>
                            <TypePrimer columns={briefing.columns} />
                        </section>
                    </div>

                    <DialogFooter>
                        <Button onClick={understand} className="h-12 w-full">
                            Saya paham, mulai
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </>
    );
}
