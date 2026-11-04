<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { Switch } from '@headlessui/vue';
import AuthCard from '@/Components/AuthCard.vue';
import Input from '@/Components/Input.vue';
import Button from '@/Components/Button.vue';
import { store as loginStore } from '@/actions/App/Http/Controllers/Auth/LoginController';
import { register } from '@/routes/index';
import { request as forgotPasswordRequest } from '@/routes/password';

withDefaults(defineProps<{
    registerEnabled?: boolean;
    resetPasswordEnabled?: boolean;
}>(), {
    registerEnabled: false,
    resetPasswordEnabled: false,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = (): void => {
    form.post(loginStore.url(), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthCard title="Login">
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

            <Input
                v-model="form.password"
                type="password"
                label="Password"
                :error="form.errors.password"
                required
                autocomplete="current-password"
            />

            <div class="flex items-center gap-2">
                <Switch
                    v-model="form.remember"
                    :class="form.remember ? 'bg-primary-600' : 'bg-white/15'"
                    class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-400"
                >
                    <span
                        :class="form.remember ? 'translate-x-4' : 'translate-x-0'"
                        class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                    />
                </Switch>
                <span class="text-sm text-slate-300">Remember me</span>
            </div>

            <div class="flex items-center justify-between pt-4">
                <Link
                    v-if="resetPasswordEnabled"
                    :href="forgotPasswordRequest.url()"
                    class="text-sm text-primary-400 hover:text-primary-300 transition-colors"
                >
                    Forgot your password?
                </Link>
                <span v-else />

                <Button
                    type="submit"
                    :loading="form.processing"
                    :disabled="form.processing"
                >
                    Login
                </Button>
            </div>
        </form>

        <div v-if="registerEnabled" class="mt-6 pt-6 border-t border-slate-700/50 text-center">
            <p class="text-slate-400 text-sm">
                Don't have an account?
                <Link :href="register.url()" class="text-primary-400 hover:text-primary-300 transition-colors">
                    Register
                </Link>
            </p>
        </div>
    </AuthCard>
</template>
