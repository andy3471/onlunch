import { ref, type Ref } from 'vue';
import type { ToastState, ToastType } from '@/Types/toast';

export interface UseToast {
    toast: Ref<ToastState | null>;
    showToast: (type: ToastType, message: string) => void;
    showSuccess: (message: string) => void;
    showError: (message: string) => void;
    showFirstError: (errors: Record<string, string | string[]>, fallback: string) => void;
    clearToast: () => void;
}

export const useToast = (): UseToast => {
    const toast = ref<ToastState | null>(null);

    const showToast = (type: ToastType, message: string): void => {
        toast.value = { type, message };
    };

    return {
        toast,
        showToast,
        showSuccess: (message: string): void => showToast('success', message),
        showError: (message: string): void => showToast('error', message),
        showFirstError: (errors: Record<string, string | string[]>, fallback: string): void => {
            const firstError = Object.values(errors)[0];
            const message = Array.isArray(firstError) ? firstError[0] : firstError;

            showToast('error', message ?? fallback);
        },
        clearToast: (): void => {
            toast.value = null;
        },
    };
};
