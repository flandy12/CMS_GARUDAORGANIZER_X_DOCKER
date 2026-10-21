<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';

const emit = defineEmits(['closePopUp']);

defineProps({
	title: String,
	message: String,
	actionTitleColor: String,
	actionMessageColor: String,
});

const closePopUp = () => {
	emit("closePopUp");
};
</script>

<template>
	<div class="fixed inset-0 z-[60] bg-blue-950/60 p-4 backdrop-blur-sm">
		<div class="flex h-full w-full items-center justify-center">
			<div class="w-full max-w-sm rounded-2xl border border-blue-100 bg-white p-6 shadow-2xl">
				<div>
					<div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-xl font-bold text-blue-700">✓</div>
					<h1 class="text-lg font-bold" :class="actionTitleColor">{{ title }}</h1>
				</div>

				<div class="mt-5 flex justify-end">
					<span :class="actionMessageColor">{{ message }}</span>
				</div>

				<div class="mt-4">
					<SecondaryButton v-on:click.prevent="closePopUp">Ok</SecondaryButton>
				</div>
			</div>
		</div>
	</div>
</template>
