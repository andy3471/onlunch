<script setup>
import { reactive, ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import BrochureLayout from '@/Layouts/BrochureLayout.vue';
import Button from '@/Components/Button.vue';

const page = usePage();
const errors = computed(() => page.props.errors || {});

const form = reactive({
    team_name: '',
    team_slug: '',
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const processing = ref(false);

function generateSlug(value) {
    return value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

function onTeamNameInput() {
    form.team_slug = generateSlug(form.team_name);
}

function handleSubmit() {
    processing.value = true;

    router.post('/register', form, {
        onFinish: () => processing.value = false,
    });
}

const inputClasses = 'w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-hidden focus:ring-2 focus:ring-primary-500 focus:border-transparent';
</script>

<template>
    <BrochureLayout>
        <div class="py-16 sm:py-24">
            <div class="max-w-md mx-auto px-4 sm:px-6">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-white">Create Your Team</h1>
                    <p class="mt-2 text-slate-400">Set up your team and start managing flexible working with FlexRota.</p>
                </div>

                <form @submit.prevent="handleSubmit" class="bg-slate-800/60 rounded-xl border border-slate-700/50 p-6">
                    <h2 class="text-lg font-semibold text-white mb-4">Team Details</h2>

                    <div class="space-y-4 mb-8">
                        <div>
                            <label for="team_name" class="block text-sm font-medium text-slate-300 mb-1">Team Name</label>
                            <input
                                id="team_name"
                                v-model="form.team_name"
                                @input="onTeamNameInput"
                                type="text"
                                required
                                :class="inputClasses"
                                placeholder="Acme Corp"
                            />
                            <p v-if="errors.team_name" class="mt-1 text-sm text-red-400">{{ errors.team_name }}</p>
                        </div>

                        <div>
                            <label for="team_slug" class="block text-sm font-medium text-slate-300 mb-1">Team URL</label>
                            <div class="flex items-center gap-0">
                                <input
                                    id="team_slug"
                                    v-model="form.team_slug"
                                    type="text"
                                    required
                                    pattern="[a-z0-9\-]+"
                                    class="flex-1 px-3 py-2 bg-slate-900 border border-slate-700 rounded-l-xl text-white placeholder-slate-500 focus:outline-hidden focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    placeholder="acme-corp"
                                />
                                <span class="px-3 py-2 bg-slate-700 border border-slate-700 rounded-r-xl text-slate-400 text-sm whitespace-nowrap">
                                    .onlunch.app
                                </span>
                            </div>
                            <p v-if="errors.team_slug" class="mt-1 text-sm text-red-400">{{ errors.team_slug }}</p>
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-white mb-4">Your Account</h2>

                    <div class="space-y-4 mb-8">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-300 mb-1">Name</label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                :class="inputClasses"
                                placeholder="Jane Smith"
                            />
                            <p v-if="errors.name" class="mt-1 text-sm text-red-400">{{ errors.name }}</p>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email</label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                :class="inputClasses"
                                placeholder="jane@acme.com"
                            />
                            <p v-if="errors.email" class="mt-1 text-sm text-red-400">{{ errors.email }}</p>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-300 mb-1">Password</label>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                required
                                :class="inputClasses"
                            />
                            <p v-if="errors.password" class="mt-1 text-sm text-red-400">{{ errors.password }}</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1">Confirm Password</label>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                required
                                :class="inputClasses"
                            />
                        </div>
                    </div>

                    <Button
                        type="submit"
                        size="lg"
                        :loading="processing"
                        :disabled="processing"
                        class="w-full"
                    >
                        Create Team
                    </Button>
                </form>
            </div>
        </div>
    </BrochureLayout>
</template>
