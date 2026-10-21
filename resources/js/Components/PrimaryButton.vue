<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex min-h-[42px] items-center justify-center gap-2 rounded-xl border border-transparent bg-blue-700 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-500/20 active:bg-blue-900 disabled:cursor-not-allowed disabled:opacity-50">
        <slot />
    </button>
</template>
