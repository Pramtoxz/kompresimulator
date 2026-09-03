import type { ImgHTMLAttributes } from 'react';
import { logo } from '@/lib/aset';

export default function AppLogoIcon({
    besar = false,
    alt = 'Kompre Simulator',
    ...props
}: ImgHTMLAttributes<HTMLImageElement> & { besar?: boolean }) {
    return (
        <img
            src={besar ? logo.besar : logo.kecil}
            alt={alt}
            width={besar ? 256 : 64}
            height={besar ? 256 : 64}
            {...props}
        />
    );
}
