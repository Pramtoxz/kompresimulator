import { Form, Head, Link } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import ReviewController from '@/actions/App/Http/Controllers/Admin/ReviewController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/admin/reviews';

type Attempt = {
    id: number;
    student: { name: string; email: string; thesis_title: string | null };
    title: string | null;
    level_label: string;
    duration_minutes: number | null;
    target_minutes: number;
    within_target: boolean;
    finished_at: string | null;
    steps: {
        step_no: number;
        label: string;
        status: string;
        duration_minutes: number | null;
    }[];
    checks: { kind: string; passed: boolean; message: string | null }[];
    auto_feedback: string | null;
    review: { score: number | null; body: string } | null;
    chats: { role: string; body: string; refused: boolean }[];
};

export default function ReviewShow({ attempt }: { attempt: Attempt }) {
    return (
        <AppLayout>
            <Head title={`Nilai ${attempt.student.name}`} />

            <div className="mx-auto w-full max-w-4xl space-y-6 p-4 sm:p-6">
                <Button asChild variant="ghost" size="sm" className="-ml-2">
                    <Link href={index()}>
                        <ChevronLeft />
                        Kembali ke daftar
                    </Link>
                </Button>

                <Heading
                    title={attempt.student.name}
                    description={`${attempt.level_label}, selesai ${attempt.finished_at ?? '-'}`}
                />

                <div className="grid gap-3 sm:grid-cols-2">
                    <Card>
                        <CardHeader className="pb-2">
                            <CardTitle className="text-base">Durasi</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-2">
                            <p className="font-mono text-3xl">
                                {attempt.duration_minutes ?? '-'}
                                <span className="text-muted-foreground text-base">
                                    {' dari '}
                                    {attempt.target_minutes} menit
                                </span>
                            </p>
                            <Badge
                                variant={
                                    attempt.within_target
                                        ? 'default'
                                        : 'destructive'
                                }
                            >
                                {attempt.within_target
                                    ? 'Masuk target'
                                    : 'Lewat target'}
                            </Badge>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="pb-2">
                            <CardTitle className="text-base">
                                Soal dan skripsi
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-1">
                            <p className="text-sm leading-relaxed">
                                {attempt.title ?? 'Soal tanpa judul'}
                            </p>
                            <p className="text-muted-foreground text-xs">
                                {attempt.student.thesis_title ?? '-'}
                            </p>
                            <p className="text-muted-foreground text-xs">
                                {attempt.student.email}
                            </p>
                        </CardContent>
                    </Card>
                </div>

                {attempt.steps.length > 0 && (
                    <Card>
                        <CardHeader className="pb-2">
                            <CardTitle className="text-base">
                                Waktu per langkah
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <ul className="divide-border divide-y text-sm">
                                {attempt.steps.map((step) => (
                                    <li
                                        key={step.step_no}
                                        className="flex justify-between gap-4 py-2"
                                    >
                                        <span>
                                            {step.step_no}. {step.label}
                                        </span>
                                        <span className="text-muted-foreground font-mono">
                                            {step.duration_minutes ?? '-'} mnt
                                        </span>
                                    </li>
                                ))}
                            </ul>
                        </CardContent>
                    </Card>
                )}

                {attempt.checks.length > 0 && (
                    <Card>
                        <CardHeader className="pb-2">
                            <CardTitle className="text-base">
                                Hasil pengecekan otomatis
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <ul className="space-y-2 text-sm">
                                {attempt.checks.map((check, urutan) => (
                                    <li
                                        key={urutan}
                                        className="flex items-start gap-2"
                                    >
                                        <Badge
                                            variant={
                                                check.passed
                                                    ? 'default'
                                                    : 'destructive'
                                            }
                                        >
                                            {check.passed ? 'Lolos' : 'Gagal'}
                                        </Badge>
                                        <span>{check.message ?? '-'}</span>
                                    </li>
                                ))}
                            </ul>
                        </CardContent>
                    </Card>
                )}

                {attempt.chats.length > 0 && (
                    <Card>
                        <CardHeader className="pb-2">
                            <CardTitle className="text-base">
                                Percakapan dengan Bg Dito
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <ul className="space-y-3 text-sm">
                                {attempt.chats.map((chat, urutan) => (
                                    <li key={urutan}>
                                        <p className="text-muted-foreground text-xs">
                                            {chat.role === 'student'
                                                ? 'Mahasiswa'
                                                : 'Bg Dito'}
                                            {chat.refused && ', ditolak'}
                                        </p>
                                        <p className="leading-relaxed">
                                            {chat.body}
                                        </p>
                                    </li>
                                ))}
                            </ul>
                        </CardContent>
                    </Card>
                )}

                {attempt.auto_feedback && (
                    <Card>
                        <CardHeader className="pb-2">
                            <CardTitle className="text-base">
                                Catatan otomatis sistem
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p className="text-muted-foreground text-sm whitespace-pre-line">
                                {attempt.auto_feedback}
                            </p>
                        </CardContent>
                    </Card>
                )}

                <Card>
                    <CardHeader className="pb-2">
                        <CardTitle className="text-base">
                            Penilaian dan masukanmu
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Form
                            {...ReviewController.store.form(attempt.id)}
                            className="space-y-4"
                        >
                            {({ processing, errors }) => (
                                <>
                                    <div className="space-y-2">
                                        <Label htmlFor="score">
                                            Nilai 0 sampai 100, boleh
                                            dikosongkan
                                        </Label>
                                        <Input
                                            id="score"
                                            name="score"
                                            type="number"
                                            min={0}
                                            max={100}
                                            defaultValue={
                                                attempt.review?.score ?? ''
                                            }
                                            className="max-w-32"
                                        />
                                        <InputError message={errors.score} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="body">
                                            Masukan untuk mahasiswa
                                        </Label>
                                        <Textarea
                                            id="body"
                                            name="body"
                                            rows={6}
                                            defaultValue={
                                                attempt.review?.body ?? ''
                                            }
                                            placeholder="Apa yang sudah bagus, apa yang perlu diulang, dan langkah mana yang perlu dilatih lagi."
                                        />
                                        <InputError message={errors.body} />
                                    </div>

                                    <Button
                                        disabled={processing}
                                        className="h-11"
                                    >
                                        {processing && <Spinner />}
                                        Simpan penilaian
                                    </Button>
                                </>
                            )}
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
