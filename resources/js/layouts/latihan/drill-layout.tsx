export default function DrillLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    return <div className="bg-background min-h-screen">{children}</div>;
}
