import { Form } from '@inertiajs/react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinner';

type Props = {
    trigger: string;
    title: string;
    description: string;
    confirmLabel: string;
    action: Record<string, unknown>;
    triggerClassName?: string;
};

export default function ConfirmDialog({
    trigger,
    title,
    description,
    confirmLabel,
    action,
    triggerClassName = 'h-11 w-full sm:h-10 sm:w-auto',
}: Props) {
    const [open, setOpen] = useState(false);

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button variant="destructive" className={triggerClassName}>
                    {trigger}
                </Button>
            </DialogTrigger>

            <DialogContent className="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>{title}</DialogTitle>
                    <DialogDescription>{description}</DialogDescription>
                </DialogHeader>

                <DialogFooter className="gap-2 sm:gap-2">
                    <DialogClose asChild>
                        <Button variant="outline" className="h-11 sm:h-10">
                            Batal
                        </Button>
                    </DialogClose>

                    <Form
                        {...action}
                        options={{ preserveScroll: true }}
                        onSuccess={() => setOpen(false)}
                    >
                        {({ processing }) => (
                            <Button
                                variant="destructive"
                                disabled={processing}
                                className="h-11 w-full sm:h-10 sm:w-auto"
                            >
                                {processing && <Spinner />}
                                {confirmLabel}
                            </Button>
                        )}
                    </Form>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
