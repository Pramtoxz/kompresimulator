import { Button } from '@/components/ui/button';
import type { GuideCard } from '@/types/latihan';
import CodeBlock from './code-block';

type Props = {
    card: GuideCard;
    number: number;
    state: 'open' | 'passed' | 'locked';
    isLast: boolean;
    showAction: boolean;
    speaking: boolean;
    onDone: () => void;
    onReopen: () => void;
    onListen: () => void;
};

export default function StepCard({
    card,
    number,
    state,
    isLast,
    showAction,
    speaking,
    onDone,
    onReopen,
    onListen,
}: Props) {
    if (state === 'locked') {
        return (
            <li className="text-muted-foreground flex items-center gap-3 rounded-lg border border-dashed px-3 py-3">
                <span className="bg-muted flex size-6 shrink-0 items-center justify-center rounded-full text-xs">
                    {number}
                </span>
                <span className="text-sm">{card.title}</span>
            </li>
        );
    }

    if (state === 'passed') {
        return (
            <li className="rounded-lg border">
                <button
                    type="button"
                    onClick={onReopen}
                    className="flex min-h-11 w-full items-center gap-3 px-3 py-2.5 text-left"
                >
                    <span className="bg-primary text-primary-foreground flex size-6 shrink-0 items-center justify-center rounded-full text-xs">
                        ✓
                    </span>
                    <span className="text-sm">{card.title}</span>
                </button>
            </li>
        );
    }

    return (
        <li className="border-primary/50 bg-card space-y-3 rounded-lg border p-4 shadow-sm">
            <div className="flex items-start gap-3">
                <span className="bg-primary text-primary-foreground flex size-6 shrink-0 items-center justify-center rounded-full text-xs font-medium">
                    {number}
                </span>
                <h3 className="flex-1 text-base leading-6 font-medium">
                    {card.title}
                </h3>

                {card.audio && (
                    <Button
                        variant="ghost"
                        size="sm"
                        onClick={onListen}
                        className="-mt-1 -mr-1 h-9 shrink-0 px-2 text-xs"
                    >
                        {speaking ? 'Hentikan' : 'Dengarkan'}
                    </Button>
                )}
            </div>

            <p className="text-sm leading-relaxed">{card.instruction}</p>

            {card.code && (
                <CodeBlock code={card.code} language={card.language} />
            )}

            {card.note && (
                <p className="text-muted-foreground border-l-2 pl-3 text-sm leading-relaxed">
                    {card.note}
                </p>
            )}

            {showAction && (
                <Button
                    variant="secondary"
                    onClick={onDone}
                    className="h-11 w-full sm:h-10 sm:w-auto"
                >
                    {isLast ? 'Sudah semua' : 'Sudah, lanjut'}
                </Button>
            )}
        </li>
    );
}
