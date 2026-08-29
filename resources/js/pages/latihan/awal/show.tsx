import { Head, router } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import WorkspaceController from '@/actions/App/Http/Controllers/Student/WorkspaceController';
import AttemptTimer from '@/components/latihan/attempt-timer';
import CodeEditor from '@/components/latihan/code-editor';
import CurrentStepDock from '@/components/latihan/current-step-dock';
import DatabasePreview from '@/components/latihan/database-preview';
import FinishForm from '@/components/latihan/finish-form';
import FileTabs from '@/components/latihan/file-tabs';
import GuidePanel from '@/components/latihan/guide-panel';
import PreviewFrame from '@/components/latihan/preview-frame';
import StepList from '@/components/latihan/step-list';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { index } from '@/routes/latihan';
import type {
    PracticeAttempt,
    PracticeProblem,
    WorkspaceDatabase,
    WorkspaceFile,
    WorkspaceGuide,
} from '@/types/latihan';

type Props = {
    attempt: PracticeAttempt;
    problem: PracticeProblem;
    guides: WorkspaceGuide[];
    files: WorkspaceFile[];
    preview: string;
    database: WorkspaceDatabase;
};

export default function WorkspaceShow({
    attempt,
    problem,
    guides,
    files,
    preview,
    database,
}: Props) {
    const [activePath, setActivePath] = useState(files[0]?.path ?? '');

    const activeFile = useMemo(
        () => files.find((file) => file.path === activePath) ?? files[0],
        [files, activePath],
    );

    const currentGuide = useMemo(
        () =>
            guides.find((guide) => guide.step_no === attempt.current_step) ??
            null,
        [guides, attempt.current_step],
    );

    const saveFile = (content: string) =>
        router.post(
            WorkspaceController.saveFile.url(attempt.id),
            { path: activeFile.path, content },
            { preserveScroll: true, preserveState: true, only: ['preview'] },
        );

    const runMigration = () =>
        router.post(
            WorkspaceController.runMigration.url(attempt.id),
            {},
            { preserveScroll: true },
        );

    const submitRow = (data: Record<string, string>) =>
        router.post(WorkspaceController.storeRow.url(attempt.id), data, {
            preserveScroll: true,
        });

    return (
        <>
            <Head title={problem.title ?? 'Ruang kerja'} />

            <div className="safe-x mx-auto flex w-full max-w-7xl flex-1 flex-col gap-5 px-4 pt-4 pb-44 sm:px-6 md:pb-8 lg:px-8">
                <div className="bg-background/95 border-sidebar-border/70 dark:border-sidebar-border sticky top-16 z-20 -mx-4 border-b px-4 pb-3 backdrop-blur sm:-mx-6 sm:px-6 lg:static lg:mx-0 lg:rounded-xl lg:border lg:p-4">
                    <AttemptTimer
                        startedAt={attempt.started_at}
                        targetMinutes={attempt.target_minutes}
                        currentStep={attempt.current_step}
                        totalSteps={attempt.steps.length}
                    />
                </div>

                <GuidePanel guide={currentGuide} />

                <div className="grid gap-5 xl:grid-cols-[1fr_20rem] xl:items-start">
                    <div className="space-y-5">
                        <Card>
                            <CardHeader className="pb-3">
                                <CardTitle className="text-base">
                                    Editor
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-3">
                                <FileTabs
                                    files={files}
                                    active={activeFile?.path ?? ''}
                                    onSelect={setActivePath}
                                />

                                {activeFile && (
                                    <CodeEditor
                                        key={activeFile.path}
                                        value={activeFile.content}
                                        onSave={saveFile}
                                    />
                                )}

                                <p className="text-muted-foreground text-xs">
                                    Kode tersimpan otomatis saat kamu klik di
                                    luar editor.
                                </p>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader className="pb-3">
                                <CardTitle className="text-base">
                                    Pratinjau
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <PreviewFrame
                                    html={preview}
                                    onSubmit={submitRow}
                                />
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader className="flex-row items-center justify-between gap-3 pb-3">
                                <CardTitle className="text-base">
                                    Tabel latihan
                                </CardTitle>
                                <Button
                                    size="sm"
                                    variant="secondary"
                                    onClick={runMigration}
                                >
                                    Jalankan migration
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <DatabasePreview database={database} />
                            </CardContent>
                        </Card>

                        <div id="selesai" className="scroll-mt-32">
                            <FinishForm attemptId={attempt.id} />
                        </div>
                    </div>

                    <div className="space-y-4 xl:sticky xl:top-24">
                        <Card>
                            <CardHeader className="pb-2">
                                <CardTitle className="text-base">
                                    Tujuh langkah
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="px-2">
                                <StepList
                                    steps={attempt.steps}
                                    currentStep={attempt.current_step}
                                />
                            </CardContent>
                        </Card>

                        <CurrentStepDock
                            attemptId={attempt.id}
                            steps={attempt.steps}
                            currentStep={attempt.current_step}
                        />
                    </div>
                </div>
            </div>
        </>
    );
}

WorkspaceShow.layout = {
    breadcrumbs: [
        { title: 'Latihan', href: index() },
        { title: 'Ruang kerja', href: index() },
    ],
};
