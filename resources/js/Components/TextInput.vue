<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: [String, Number],
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="min-h-[44px] rounded-xl border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 hover:border-blue-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500 file:-my-2.5 file:-ms-3.5 file:me-3 file:border-0 file:border-e file:border-slate-200 file:bg-blue-50 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
