<script setup lang="ts">
import { computed } from 'vue';
import { useForm, Link, usePage } from '@inertiajs/vue3';
import AuthCard from '@/Components/AuthCard.vue';
import Input from '@/Components/Input.vue';
import Button from '@/Components/Button.vue';
import { email as sendResetLink } from '@/routes/password';
import { login } from '@/routes/index';

const page = usePage();
const status = computed(() => page.props['status'] as string | undefined);

const form = useForm({
    email: '',
});

const submit = (): void => {
    form.post(sendResetLink.url());
};
</script>

<template>
    <AuthCard title="Forgot Password">
        <p class="text-slate-400 text-sm mb-6">
            Forgot your password? No problem. Just enter your email address and we'll send you a password reset link.
        </p>

        <div
            v-if="status"
            class="mb-6 p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400 text-sm"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <Input
                v-model="form.email"
                type="email"
                label="Email Address"
                :error="form.errors.email"
                required
                autocomplete="email"
                autofocus
            />

            <div class="flex items-center justify-between pt-4">
                <Link
                    :href="login.url()"
                    class="text-sm text-slate-400 hover:text-slate-300 transition-colors"
                >
                    Back to login
                </Link>

                <Button
                    type="submit"
                    :loading="form.processing"
                    :disabled="form.processing"
                >
                    Send Reset Link
                </Button>
            </div>
        </form>
    </AuthCard>
</template>
