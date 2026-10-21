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
	<div class="fixed top-0 left-0 pop-up-wrapper w-full h-full z-10">
		<div class="flex w-full h-full justify-center items-center">
			<div class="bg-white rounded-lg p-4">
				<div>
					<h1 class="font-bold" :class="actionTitleColor">{{ title }}</h1>
				</div>

				<div class="mt-4">
					<span :class="actionMessageColor">{{ message }}</span>
				</div>

				<div class="mt-4">
					<SecondaryButton v-on:click.prevent="closePopUp">Ok</SecondaryButton>
				</div>
			</div>
		</div>
	</div>
</template>