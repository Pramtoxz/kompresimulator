import { Head, router } from '@inertiajs/react';
import { useCallback, useMemo, useRef, useState } from 'react';
import WorkspaceController from '@/actions/App/Http/Controllers/Student/WorkspaceController';
import DrillHeader from '@/components/latihan/drill-header';
import FinishForm from '@/components/latihan/finish-form';
import type { PreviewHandle } from '@/components/latihan/preview-frame';
import StepDock from '@/components/latihan/step-dock';
import StepInstruction from '@/components/latihan/step-instruction';
import StepWorkbench from '@/components/latihan/step-workbench';
import type {
    Briefing,
    PracticeAttempt,
    PracticeProblem,
    WorkspaceCheck,
    WorkspaceDatabase,
    WorkspaceFile,
    WorkspaceGuide,
    WorkspaceTestCase,
} from '@/types/latihan';

type Props = {
    attempt: PracticeAttempt;
    problem: PracticeProblem;
    guides: WorkspaceGuide[];
    briefing: Briefing | null;
    briefingAudio: string[];
    files: WorkspaceFile[];
    preview: string;
    database: WorkspaceDatabase;
    checks: WorkspaceCheck[];
    testCases: WorkspaceTestCase[];
    totalField: string | null;
    guided: boolean;
};

export default function WorkspaceShow({
    attempt,
    problem,
    guides,
    briefing,
    briefingAudio,
    files,
    preview,
    database,
    checks,
    testCases,
    totalField,
    guided,
}: Props) {
    const [checking, setChecking] = useState(false);
    const previewRef = useRef<PreviewHandle>(null);

    const step = useMemo(
        () => attempt.steps.find((item) => item.step_no === attempt.current_step),
        [attempt.steps, attempt.current_step],
    );

    const guide = useMemo(
        () => guides.find((item) => item.step_no === attempt.current_step) ?? null,
        [guides, attempt.current_step],
    );

    const file = useMemo(
        () => files.find((item) => item.step_key === step?.step_key),
        [files, step],
    );

    const saveFile = (content: string) => {
        if (!file) {
            return;
        }

        router.post(
            WorkspaceController.saveFile.url(attempt.id),
            { path: file.path, content },
            { preserveScroll: true, preserveState: true, only: ['preview'] },
        );
    };

    const runMigration = () =>
        router.post(
            WorkspaceController.runMigration.url(attempt.id),
            {},
            { preserveScroll: true },
        );

    const createTable = () =>
        router.post(
            WorkspaceController.createTable.url(attempt.id),
            {},
            { preserveScroll: true },
        );

    const submitRow = useCallback(
        (data: Record<string, string>) =>
            router.post(WorkspaceController.storeRow.url(attempt.id), data, {
                preserveScroll: true,
            }),
        [attempt.id],
    );

    const runChecks = async () => {
        setChecking(true);

        const results = [];

        for (const testCase of testCases) {
            const total = totalField
                ? await previewRef.current?.runCase(
                      testCase.id,
                      testCase.inputs,
                      totalField,
                  )
                : null;

            results.push({
                test_case_id: testCase.id,
                actual_total: total ?? null,
            });
        }

        router.post(
            WorkspaceController.runChecks.url(attempt.id),
            { results },
            { preserveScroll: true, onFinish: () => setChecking(false) },
        );
    };

    const isLastStep = attempt.current_step >= attempt.steps.length;

    return (
        <>
            <Head title={`Langkah ${attempt.current_step} — ${problem.title ?? 'Latihan'}`} />

            <DrillHeader
                steps={attempt.steps}
                currentStep={attempt.current_step}
                startedAt={attempt.started_at}
                targetMinutes={attempt.target_minutes}
                showTimer={!guided}
            />

            <main className="safe-x mx-auto w-full max-w-5xl space-y-8 px-4 pt-6 pb-32 sm:px-6">
                <StepInstruction
                    guide={guide}
                    briefing={briefing}
                    briefingAudio={briefingAudio}
                    attemptId={attempt.id}
                    guided={guided}
                />

                <StepWorkbench
                    stepKey={step?.step_key ?? ''}
                    file={file}
                    onSaveFile={saveFile}
                    onRunMigration={runMigration}
                    onCreateTable={createTable}
                    onSubmitRow={submitRow}
                    onRunChecks={runChecks}
                    previewRef={previewRef}
                    preview={preview}
                    database={database}
                    checks={checks}
                    checking={checking}
                    guided={guided}
                />

                {isLastStep && <FinishForm attemptId={attempt.id} />}
            </main>

            <StepDock
                attemptId={attempt.id}
                problem={problem}
                label="Langkah selesai"
                isLastStep={isLastStep}
            />
        </>
    );
}
