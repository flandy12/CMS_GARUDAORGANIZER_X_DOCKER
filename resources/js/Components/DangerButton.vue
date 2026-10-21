<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex min-h-[42px] items-center justify-center gap-2 rounded-xl border border-transparent bg-rose-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-sm transition hover:bg-rose-700 active:bg-rose-800 focus:outline-none focus:ring-4 focus:ring-rose-500/20 disabled:cursor-not-allowed disabled:opacity-50">
        <slot />
    </button>
</template>
