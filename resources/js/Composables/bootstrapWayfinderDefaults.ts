import { setUrlDefaults } from '@/wayfinder';

const DEFAULT_APP_DOMAIN = 'localhost';

export function bootstrapWayfinderDefaults(): void {
    if (typeof window === 'undefined') {
        return;
    }

    const host = window.location.hostname;
    const suffix = `.${DEFAULT_APP_DOMAIN}`;

    if (host !== DEFAULT_APP_DOMAIN && host.endsWith(suffix)) {
        setUrlDefaults({ tenant: host.slice(0, -suffix.length) });
    }
}
