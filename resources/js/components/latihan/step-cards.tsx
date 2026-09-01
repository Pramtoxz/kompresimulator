import { useEffect, useState } from 'react';
import { Button } from '@/components/ui/button';
import useNarration from '@/hooks/use-narration';
import type { GuideCard } from '@/types/latihan';
import StepCard from './step-card';

type Props = {
    cards: GuideCard[];
    stepKey: string;
};

export default function StepCards({ cards, stepKey }: Props) {
    const [open, setOpen] = useState(0);
    const [reached, setReached] = useState(0);
    const [recap, setRecap] = useState(false);
    const narration = useNarration();

    useEffect(() => {
        setOpen(0);
        setReached(0);
        setRecap(false);
    }, [stepKey]);

    if (cards.length === 0) {
        return null;
    }

    const hasAudio = cards.some((card) => card.audio !== null);

    const finish = (index: number) => {
        narration.stop();

        if (index === cards.length - 1) {
            setRecap(true);

            return;
        }

        setOpen(index + 1);
        setReached((current) => Math.max(current, index + 1));

        if (narration.enabled) {
            narration.play([cards[index + 1]?.audio ?? null]);
        }
    };

    const listen = (index: number) => {
        if (narration.playing) {
            narration.stop();

            return;
        }

        narration.play([cards[index].audio]);
    };

    const stateFor = (index: number) => {
        if (recap || index === open) {
            return 'open' as const;
        }

        return index <= reached ? ('passed' as const) : ('locked' as const);
    };

    return (
        <section className="space-y-3">
            <div className="flex items-center justify-between gap-3">
                <p className="text-muted-foreground text-xs">
                    {recap
                        ? `Semua ${cards.length} bagian sudah kamu lewati. Baca ulang sekali dari atas, lalu tekan Langkah selesai di bawah.`
                        : `Bagian ${Math.min(open + 1, cards.length)} dari ${cards.length}`}
                </p>

                {hasAudio && (
                    <Button
                        variant="ghost"
                        size="sm"
                        onClick={narration.toggle}
                        className="text-muted-foreground h-9 shrink-0 px-2 text-xs"
                    >
                        Suara {narration.enabled ? 'nyala' : 'mati'}
                    </Button>
                )}
            </div>

            <ol className="space-y-2">
                {cards.map((card, index) => (
                    <StepCard
                        key={card.title}
                        card={card}
                        number={index + 1}
                        state={stateFor(index)}
                        isLast={index === cards.length - 1}
                        showAction={!recap}
                        speaking={narration.playing && index === open}
                        onDone={() => finish(index)}
                        onReopen={() => setOpen(index)}
                        onListen={() => listen(index)}
                    />
                ))}
            </ol>
        </section>
    );
}
