<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(defineProps<{
    modelValue?: string | number;
    type?: string;
    label?: string;
    error?: string;
    placeholder?: string;
    required?: boolean;
    disabled?: boolean;
    autocomplete?: string;
    autofocus?: boolean;
    step?: string | number;
}>(), {
    modelValue: '',
    type: 'text',
    label: '',
    error: '',
    placeholder: '',
    required: false,
    disabled: false,
    autocomplete: '',
    autofocus: false,
    step: undefined,
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const inputClasses = computed(() => [
    'input',
    props.error ? 'input-error' : '',
]);

const updateValue = (event: Event): void => {
    emit('update:modelValue', (event.target as HTMLInputElement).value);
};
</script>

<template>
    <div class="space-y-1">
        <label v-if="label" class="block text-sm font-medium text-slate-300">
            {{ label }}
            <span v-if="required" class="text-red-400">*</span>
        </label>
        <input
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            :required="required"
            :disabled="disabled"
            :autocomplete="autocomplete"
            :autofocus="autofocus"
            :step="step"
            :class="inputClasses"
            @input="updateValue"
        />
        <p v-if="error" class="text-sm text-red-400">
            {{ error }}
        </p>
    </div>
</template>
