import { Form } from '@inertiajs/react';
import { useState } from 'react';
import AttemptController from '@/actions/App/Http/Controllers/Student/AttemptController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

export default function FinishForm({ attemptId }: { attemptId: number }) {
    const [source, setSource] = useState<'timer' | 'manual'>('timer');

    return (
        <Card>
            <CardHeader>
                <CardTitle className="text-base">Tandai selesai</CardTitle>
            </CardHeader>
            <CardContent>
                <Form
                    {...AttemptController.finish.form(attemptId)}
                    className="space-y-4"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="space-y-2">
                                <Label>Sumber durasi</Label>
                                <div className="flex flex-col gap-2 text-sm">
                                    <label className="flex items-center gap-2">
                                        <input
                                            type="radio"
                                            name="duration_source"
                                            value="timer"
                                            checked={source === 'timer'}
                                            onChange={() => setSource('timer')}
                                        />
                                        Pakai timer aplikasi
                                    </label>
                                    <label className="flex items-center gap-2">
                                        <input
                                            type="radio"
                                            name="duration_source"
                                            value="manual"
                                            checked={source === 'manual'}
                                            onChange={() => setSource('manual')}
                                        />
                                        Isi sendiri durasinya
                                    </label>
                                </div>
                                <InputError message={errors.duration_source} />
                            </div>

                            {source === 'manual' && (
                                <div className="grid gap-2">
                                    <Label htmlFor="manual_minutes">
                                        Durasi (menit)
                                    </Label>
                                    <Input
                                        id="manual_minutes"
                                        name="manual_minutes"
                                        type="number"
                                        min={1}
                                        max={600}
                                        required
                                    />
                                    <InputError
                                        message={errors.manual_minutes}
                                    />
                                </div>
                            )}

                            <Button disabled={processing} className="w-full">
                                {processing && <Spinner />}
                                Selesai
                            </Button>
                        </>
                    )}
                </Form>
            </CardContent>
        </Card>
    );
}
