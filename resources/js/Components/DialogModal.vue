<script setup>
import Modal from './Modal.vue';

const emit = defineEmits(['close']);

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const close = () => {
    emit('close');
};
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <div class="border-b border-slate-200 px-5 py-5 sm:px-7">
            <div class="text-lg font-bold text-slate-900">
                <slot name="title" />
            </div>

            <div class="cms-form mt-5 text-sm text-slate-600">
                <slot name="content" />
            </div>
        </div>

        <div class="sticky bottom-0 flex flex-row justify-end gap-2 border-t border-slate-200 bg-slate-50 px-5 py-4 text-end sm:px-7">
            <slot name="footer" />
        </div>
    </Modal>
</template>
