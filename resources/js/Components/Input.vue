<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: '',
    },
    type: {
        type: String,
        default: 'text',
    },
    label: {
        type: String,
        default: '',
    },
    error: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    autocomplete: {
        type: String,
        default: '',
    },
    autofocus: {
        type: Boolean,
        default: false,
    },
    step: {
        type: [String, Number],
        default: undefined,
    },
});

const emit = defineEmits(['update:modelValue']);

const inputClasses = computed(() => [
    'input',
    props.error ? 'input-error' : '',
]);

const updateValue = (event) => {
    emit('update:modelValue', event.target.value);
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
