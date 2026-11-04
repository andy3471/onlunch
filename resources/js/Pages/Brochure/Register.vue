<script setup lang="ts">
import { reactive, ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import BrochureLayout from '@/Layouts/BrochureLayout.vue';
import Button from '@/Components/Button.vue';
import { store as registerSite } from '@/routes/sites';

const page = usePage();
const errors = computed(() => page.props.errors);

const form = reactive({
    team_name: '',
    team_slug: '',
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const processing = ref(false);

function generateSlug(value: string): string {
    return value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

function onTeamNameInput(): void {
    form.team_slug = generateSlug(form.team_name);
}

function handleSubmit(): void {
    processing.value = true;

    router.post(registerSite.url(), form, {
        onFinish: () => processing.value = false,
    });
}
</script>

<template>
    <BrochureLayout>
        <div class="px-4 py-14 sm:px-6 sm:py-20">
            <div class="mx-auto max-w-md">
                <div class="mb-8">
                    <h1 class="text-2xl font-semibold tracking-tight text-white sm:text-3xl">
                        Create your team
                    </h1>
                    <p class="mt-2 text-sm leading-relaxed text-slate-400">
                        Set up FlexRota for your team and start scheduling the day.
                    </p>
                </div>

                <form
                    class="card space-y-8 p-6"
                    @submit.prevent="handleSubmit"
                >
                    <fieldset class="space-y-4">
                        <legend class="text-sm font-semibold text-white">Team</legend>

                        <div>
                            <label
                                for="team_name"
                                class="mb-1 block text-sm font-medium text-slate-300"
                            >Team name</label>
                            <input
                                id="team_name"
                                v-model="form.team_name"
                                type="text"
                                required
                                class="input"
                                placeholder="Acme Corp"
                                @input="onTeamNameInput"
                            >
                            <p
                                v-if="errors['team_name']"
                                class="mt-1 text-sm text-red-400"
                            >{{ errors['team_name'] }}</p>
                        </div>

                        <div>
                            <label
                                for="team_slug"
                                class="mb-1 block text-sm font-medium text-slate-300"
                            >Team URL</label>
                            <div class="flex items-center">
                                <input
                                    id="team_slug"
                                    v-model="form.team_slug"
                                    type="text"
                                    required
                                    pattern="[a-z0-9\-]+"
                                    class="input rounded-r-none"
                                    placeholder="acme-corp"
                                >
                                <span class="rounded-r-lg border border-l-0 border-white/10 bg-white/5 px-3 py-2 text-sm whitespace-nowrap text-slate-400">
                                    .onlunch.app
                                </span>
                            </div>
                            <p
                                v-if="errors['team_slug']"
                                class="mt-1 text-sm text-red-400"
                            >{{ errors['team_slug'] }}</p>
                        </div>
                    </fieldset>

                    <fieldset class="space-y-4">
                        <legend class="text-sm font-semibold text-white">Your account</legend>

                        <div>
                            <label
                                for="name"
                                class="mb-1 block text-sm font-medium text-slate-300"
                            >Name</label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                class="input"
                                placeholder="Jane Smith"
                            >
                            <p
                                v-if="errors['name']"
                                class="mt-1 text-sm text-red-400"
                            >{{ errors['name'] }}</p>
                        </div>

                        <div>
                            <label
                                for="email"
                                class="mb-1 block text-sm font-medium text-slate-300"
                            >Email</label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                class="input"
                                placeholder="jane@acme.com"
                            >
                            <p
                                v-if="errors['email']"
                                class="mt-1 text-sm text-red-400"
                            >{{ errors['email'] }}</p>
                        </div>

                        <div>
                            <label
                                for="password"
                                class="mb-1 block text-sm font-medium text-slate-300"
                            >Password</label>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                required
                                class="input"
                            >
                            <p
                                v-if="errors['password']"
                                class="mt-1 text-sm text-red-400"
                            >{{ errors['password'] }}</p>
                        </div>

                        <div>
                            <label
                                for="password_confirmation"
                                class="mb-1 block text-sm font-medium text-slate-300"
                            >Confirm password</label>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                required
                                class="input"
                            >
                        </div>
                    </fieldset>

                    <Button
                        type="submit"
                        size="lg"
                        :loading="processing"
                        :disabled="processing"
                        class="w-full"
                    >
                        Create team
                    </Button>
                </form>
            </div>
        </div>
    </BrochureLayout>
</template>
