<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message" role="alert">
        <p class="flex items-start gap-1.5 text-xs font-medium text-rose-600">
            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 3.6 2.6 17A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-3L13.7 3.6a2 2 0 0 0-3.4 0Z" /></svg>
            {{ message }}
        </p>
    </div>
</template>
