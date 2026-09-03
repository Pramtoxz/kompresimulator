import { Link } from '@inertiajs/react';
import { Check } from 'lucide-react';
import { useState } from 'react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { show } from '@/routes/latihan/soal';
import type { PracticeProblemChoice } from '@/types/latihan';

type Props = {
    soal: PracticeProblemChoice[];
    terkunci: boolean;
};

export default function SoalPicker({ soal, terkunci }: Props) {
    const [open, setOpen] = useState(false);
    const selesai = soal.filter((item) => item.done).length;

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button disabled={terkunci} className="h-12 w-full">
                    Pilih soal ujian
                </Button>
            </DialogTrigger>

            <DialogContent className="max-h-[85svh] overflow-y-auto sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Pilih soal yang mau dikerjakan</DialogTitle>
                    <DialogDescription>
                        {selesai} dari {soal.length} soal sudah pernah kamu
                        selesaikan. Boleh dikerjakan berurutan atau
                        lompat-lompat.
                    </DialogDescription>
                </DialogHeader>

                <ul className="divide-border divide-y">
                    {soal.map((item, urutan) => (
                        <li
                            key={item.id}
                            className="flex items-start gap-3 py-3"
                        >
                            <span
                                className={`mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full text-xs font-medium ${
                                    item.done
                                        ? 'bg-primary text-primary-foreground'
                                        : 'bg-muted text-muted-foreground'
                                }`}
                            >
                                {item.done ? (
                                    <Check className="size-3.5" />
                                ) : (
                                    urutan + 1
                                )}
                            </span>

                            <div className="min-w-0 flex-1 space-y-1">
                                <p className="text-sm leading-snug font-medium">
                                    {item.title ?? 'Soal tanpa judul'}
                                </p>

                                {item.done ? (
                                    <div className="flex flex-wrap items-center gap-2">
                                        <Badge
                                            variant={
                                                item.within_target
                                                    ? 'default'
                                                    : 'destructive'
                                            }
                                        >
                                            {item.within_target
                                                ? 'Masuk target'
                                                : 'Lewat target'}
                                        </Badge>
                                        <span className="text-muted-foreground text-xs">
                                            {item.duration_minutes ?? '—'} menit
                                            {item.attempts > 1 &&
                                                ` · ${item.attempts}x dikerjakan`}
                                        </span>
                                    </div>
                                ) : (
                                    <p className="text-muted-foreground text-xs">
                                        Belum dikerjakan
                                    </p>
                                )}
                            </div>

                            <Button
                                asChild
                                variant={item.done ? 'outline' : 'default'}
                                size="sm"
                                className="mt-0.5 h-9 shrink-0"
                            >
                                <Link href={show(item.id)}>
                                    {item.done ? 'Ulangi' : 'Mulai'}
                                </Link>
                            </Button>
                        </li>
                    ))}
                </ul>
            </DialogContent>
        </Dialog>
    );
}
