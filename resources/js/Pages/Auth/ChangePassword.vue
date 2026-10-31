<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import AuthCard from '@/Components/AuthCard.vue';
import Input from '@/Components/Input.vue';
import Button from '@/Components/Button.vue';
import { update as updatePassword } from '@/actions/App/Http/Controllers/User/PasswordController';
import { home } from '@/routes/index';

const form = useForm({
    currentpassword: '',
    newpassword: '',
    newpassword_confirmation: '',
});

const submit = (): void => {
    form.put(updatePassword.url(), {
        preserveScroll: false,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <AuthCard title="Change Password">
        <form @submit.prevent="submit" class="space-y-4">
            <Input
                v-model="form.currentpassword"
                type="password"
                label="Current Password"
                :error="form.errors.currentpassword"
                required
                autocomplete="current-password"
                autofocus
            />

            <Input
                v-model="form.newpassword"
                type="password"
                label="New Password"
                :error="form.errors.newpassword"
                required
                autocomplete="new-password"
            />

            <Input
                v-model="form.newpassword_confirmation"
                type="password"
                label="Confirm New Password"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center justify-between pt-4">
                <Link
                    :href="home.url()"
                    class="text-sm text-slate-400 hover:text-slate-300 transition-colors"
                >
                    Cancel
                </Link>

                <Button
                    type="submit"
                    :loading="form.processing"
                    :disabled="form.processing"
                >
                    Change Password
                </Button>
            </div>
        </form>
    </AuthCard>
</template>
