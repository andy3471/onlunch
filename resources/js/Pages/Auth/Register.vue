<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import AuthCard from '@/Components/AuthCard.vue';
import Input from '@/Components/Input.vue';
import Button from '@/Components/Button.vue';
import { store as registerStore } from '@/actions/App/Http/Controllers/Auth/RegisterController';
import { login } from '@/routes/index';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = (): void => {
    form.post(registerStore.url(), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthCard title="Register">
        <form @submit.prevent="submit" class="space-y-4">
            <Input
                v-model="form.name"
                type="text"
                label="Name"
                :error="form.errors.name"
                required
                autocomplete="name"
                autofocus
            />

            <Input
                v-model="form.email"
                type="email"
                label="Email Address"
                :error="form.errors.email"
                required
                autocomplete="email"
            />

            <Input
                v-model="form.password"
                type="password"
                label="Password"
                :error="form.errors.password"
                required
                autocomplete="new-password"
            />

            <Input
                v-model="form.password_confirmation"
                type="password"
                label="Confirm Password"
                required
                autocomplete="new-password"
            />

            <div class="flex justify-end pt-4">
                <Button
                    type="submit"
                    :loading="form.processing"
                    :disabled="form.processing"
                >
                    Register
                </Button>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-700/50 text-center">
            <p class="text-slate-400 text-sm">
                Already have an account?
                <Link :href="login.url()" class="text-primary-400 hover:text-primary-300 transition-colors">
                    Login
                </Link>
            </p>
        </div>
    </AuthCard>
</template>
