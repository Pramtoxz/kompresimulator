import AppLogoIcon from '@/components/app-logo-icon';
import { logo } from '@/lib/aset';

type Props = {
    title?: string;
    description?: string;
    children: React.ReactNode;
};

export default function AuthSplitCard({ title, description, children }: Props) {
    return (
        <div className="bg-muted/40 safe-x safe-t safe-b flex min-h-svh items-center justify-center p-4 sm:p-6">
            <div className="bg-background grid w-full max-w-5xl overflow-hidden rounded-2xl border shadow-xl md:grid-cols-[1.05fr_1fr]">
                <aside className="relative hidden flex-col items-center justify-center overflow-hidden px-10 py-12 text-center md:flex lg:px-12">
                    <div
                        aria-hidden
                        className="absolute inset-0"
                        style={{
                            background:
                                'linear-gradient(155deg, oklch(0.44 0.10 245) 0%, oklch(0.34 0.09 262) 55%, oklch(0.26 0.07 280) 100%)',
                        }}
                    />
                    <div
                        aria-hidden
                        className="absolute -top-28 -right-24 size-80 rounded-full bg-white/[0.07]"
                    />
                    <div
                        aria-hidden
                        className="absolute -bottom-32 -left-28 size-96 rounded-full bg-white/[0.05]"
                    />

                    <div className="relative flex w-full max-w-sm flex-col items-center gap-6">
                        <img
                            src={logo.auth}
                            alt="Kompre Simulator"
                            className="naik-halus size-36 object-contain drop-shadow-2xl lg:size-44"
                        />

                        <p
                            className="naik-halus text-3xl leading-[1.15] font-semibold tracking-tight text-balance text-white lg:text-4xl"
                            style={{ animationDelay: '120ms' }}
                        >
                            Gagal di siko perai nyo.
                        </p>

                        <p
                            className="naik-halus text-base leading-relaxed text-white/80"
                            style={{ animationDelay: '200ms' }}
                        >
                            Nyo Nan Maha tu mah pas hari-H. Mangkonyo ngulang tu
                            di siko. Nda Mangulang Kompre Do. Jaan Jadi Donatur
                            Tetap Kampus Juo baru lai.
                        </p>

                        <p
                            className="naik-halus border-y border-white/15 py-4 text-sm leading-relaxed text-white/75 italic"
                            style={{ animationDelay: '280ms' }}
                        >
                            Baraja Awak, Batanggang Awak, Pandai Awak, Lulus
                            Awak, Awak Juo Nan ka Di gunjiangan dek tetangga
                            nyo.
                        </p>

                        <figure
                            className="naik-halus w-full space-y-2 rounded-xl border border-white/10 bg-black/20 px-5 py-4"
                            style={{ animationDelay: '380ms' }}
                        >
                            <figcaption className="text-xs font-medium text-white/65">
                                "Kata Kata Hari Mas Didonggg"
                            </figcaption>
                            <blockquote className="text-sm leading-relaxed text-white/85">
                                "Jangan pernah semangat,tetaplah putus asa,
                                jadilah beban keluarga. Adios Parmosa
                                Elcontroller"
                            </blockquote>
                        </figure>
                    </div>
                </aside>

                <main className="flex flex-col justify-center p-6 sm:p-10">
                    <div className="naik-halus mx-auto w-full max-w-sm">
                        <div className="mb-8 flex justify-center md:hidden">
                            <AppLogoIcon
                                besar
                                className="size-24 object-contain"
                            />
                        </div>

                        <div className="mb-6 space-y-1.5">
                            <h1 className="text-2xl font-semibold tracking-tight">
                                {title}
                            </h1>
                            {description && (
                                <p className="text-muted-foreground text-sm leading-relaxed">
                                    {description}
                                </p>
                            )}
                        </div>

                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}
