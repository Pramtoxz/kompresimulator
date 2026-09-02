import { useState } from 'react';
import ExamSheet from '@/components/soal/exam-sheet';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import type { PracticeProblem } from '@/types/latihan';

export default function SoalSheet({ problem }: { problem: PracticeProblem }) {
    const [open, setOpen] = useState(false);

    return (
        <Sheet open={open} onOpenChange={setOpen}>
            <SheetTrigger asChild>
                <Button
                    variant="outline"
                    className="h-12 flex-1 sm:flex-none"
                    data-tour="soal"
                >
                    Lihat soal
                </Button>
            </SheetTrigger>

            <SheetContent
                side="right"
                className="safe-x safe-b w-full overflow-y-auto sm:max-w-xl"
            >
                <SheetHeader>
                    <SheetTitle>Soal</SheetTitle>
                </SheetHeader>

                <div className="px-4 pb-6">
                    <ExamSheet
                        title={problem.title}
                        brief={problem.brief}
                        requirements={problem.requirements}
                        formFields={problem.form_fields}
                        lookup={problem.lookup}
                        rules={problem.rules}
                        table={problem.table}
                    />
                </div>
            </SheetContent>
        </Sheet>
    );
}
