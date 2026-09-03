import { Lottie, LottieDisplay, LottieError } from 'lottie-react';
import useReducedMotion from '@/hooks/use-reduced-motion';

type Props = {
    src: string;
    alt: string;
    className?: string;
    loop?: boolean;
};

export default function LottieArt({ src, alt, className, loop = true }: Props) {
    const reduced = useReducedMotion();

    return (
        <Lottie
            src={src}
            loop={reduced ? false : loop}
            autoplay={!reduced}
            className={className}
            aria-label={alt}
            role="img"
        >
            <LottieDisplay />
            <LottieError />
        </Lottie>
    );
}
