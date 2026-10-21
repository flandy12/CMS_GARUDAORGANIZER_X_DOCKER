<script setup>
import { computed } from 'vue';

const props = defineProps({
    value: String,
});

const required = computed(() => props.value?.trim().endsWith('*'));
const label = computed(() => required.value ? props.value.trim().slice(0, -1).trim() : props.value);
</script>

<template>
    <label class="mb-2 block text-sm font-semibold text-slate-700">
        <span v-if="value">{{ label }} <span v-if="required" class="text-rose-500" aria-label="wajib">*</span></span>
        <span v-else><slot /></span>
    </label>
</template>
