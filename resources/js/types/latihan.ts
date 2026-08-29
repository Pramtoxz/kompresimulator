export type PracticeStep = {
    step_no: number;
    step_key: string;
    label: string;
    status: 'pending' | 'in_progress' | 'done';
    duration_seconds: number | null;
};

export type PracticeAttempt = {
    id: number;
    status: string;
    current_step: number;
    target_minutes: number;
    started_at: string;
    duration_seconds: number | null;
    duration_source: string | null;
    steps: PracticeStep[];
};

export type PracticeColumn = {
    name: string;
    type: string;
    nullable: boolean;
};

export type PracticeRule = {
    key: string;
    description: string;
    expression: string;
};

export type PracticeRate = {
    key: string;
    option: string;
    amount: number;
};

export type PracticeProblem = {
    title: string | null;
    brief: string | null;
    requirements: string[];
    table: string | null;
    columns: PracticeColumn[];
    rules: PracticeRule[];
    rates: PracticeRate[];
};

export type PracticeHistoryRow = {
    id: number;
    title: string | null;
    level_label: string;
    duration_minutes: number | null;
    target_minutes: number;
    within_target: boolean;
    finished_at: string | null;
};

export type PracticeSummary = {
    available: number | null;
    running: number | null;
    history: PracticeHistoryRow[];
};
