<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PopUpMessage from '@/Components/PopUp/PopUpMessage.vue';
import ServiceForm from './Partials/ServiceForm.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
	services: {
		type: Object,
		default: null,
	},
});

const actionStatus = ref(false), actionTitle = ref(''), actionMessage = ref(''), actionTitleColor = ref(''), actionMessageColor = ref(''), confirmingDeletion = ref(false), processingDelete = ref(false), deletedId = ref(null), deleteLabel = ref('');

const closePopUp = () => {
	actionStatus.value = false;
	actionTitle.value = '';
	actionMessage.value = '';
	actionTitleColor.value = '';
	actionMessageColor.value = '';

	location.reload();
};

const confirmDeletion = (id, label) => {
	deleteLabel.value = label;
	deletedId.value = id;
	confirmingDeletion.value = true;
};

const closeModalDelete = () => {
	deleteLabel.value = null;
	deletedId.value = null;
    confirmingDeletion.value = false;
};

const removeData = () => {
	processingDelete.value = true;

	axios.post(route("service.delete", { service: deletedId.value }), { _method: 'DELETE' }).then((response) => {
		const { title, message } = response.data;

		closeModalDelete();

		actionTitle.value = title;
		actionMessage.value = message;
		actionTitleColor.value = "text-green-900";
		actionMessageColor.value = "text-green-700";
		actionStatus.value = true;
		processingDelete.value = false;
	}).catch((error) => {
		const { statusText } = error.response;

		actionTitle.value = "Error Delete Home";
		actionMessage.value = statusText;
		actionTitleColor.value = "text-red-900";
		actionMessageColor.value = "text-red-700";
		actionStatus.value = true;
		processingDelete.value = false;
	});
};

const successFormSubmit = (title, message) => {
	actionStatus.value = true;
	actionTitle.value = title;
	actionMessage.value = message;
	actionTitleColor.value = "text-green-900";
	actionMessageColor.value = "text-green-700";
};
</script>

<template>
	<AppLayout title="Service">
		<template #header>
			<h1 class="font-semibold text-xl text-gray-800 leading-tight">
				Service
			</h1>
		</template>

		<div class="py-12">
			<PopUpMessage
				v-if="actionStatus" 
				:title="actionTitle"
				:message="actionMessage"
				:actionTitleColor="actionTitleColor"
				:actionMessageColor="actionMessageColor"
				@closePopUp="closePopUp"
			/>

			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
					<div>
        				<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        					<div>
								<ServiceForm title="Create Service Page" @success="successFormSubmit">
									<PrimaryButton>Add</PrimaryButton>
								</ServiceForm>
							</div>

							<div v-if="services">
								<div class="grid grid-cols-2 gap-2 mt-10" v-for="(result, key) in services.data" :key="key">
									<div>
										<div>
											<img :src="result.banner" class="w-full">
										</div>
										<div class="mt-2">
											<span v-html="result.wording"></span>
										</div>
									</div>
									<div class="flex gap-2">
										<div>
											<h1 class="font-semibold">{{ result.title }}</h1>
											<span v-html="result.description"></span>
											<br />
											<img :src="result.image"> <br />
											<div class="font-semibold">{{ result.type }}</div>

											<div class="mt-4">
												<h1 class="font-semibold">Other Images:</h1>

												<div class="mt-2 grid grid-cols-2 gap-2">
													<div v-for="(other_image, otherImageKey) in result.other_images" :key="otherImageKey">
														<img :src="other_image.image" class="w-32">
														<h1 class="font-semibold">{{ other_image.title }}</h1>
													</div>
												</div>
											</div>
										</div>
										<div>
											<div>
												<ServiceForm title="Update Service Page" :id="result.id" @success="successFormSubmit">
													<PrimaryButton>Edit</PrimaryButton>
												</ServiceForm>
											</div>

											<div class="mt-4">
												<DangerButton v-on:click.prevent="confirmDeletion(result.id, result.type)">Delete</DangerButton>
											</div>
										</div>
									</div>
								</div>
							</div>
        				</div>
        			</div>
        		</div>
        	</div>

        	<ConfirmationModal :show="confirmingDeletion" @close="closeModalDelete">
	            <template #title>
	                {{ 'Delete Service '+deleteLabel }}
	            </template>

	            <template #content>
	                {{ 'Are you sure want to delete '+deleteLabel+' service content?' }}
	            </template>

	            <template #footer>
	                <SecondaryButton v-on:click.prevent="closeModalDelete">
	                    {{ 'Cancel' }}
	                </SecondaryButton>

	                <DangerButton
	                    class="ml-3"
	                    :class="{ 'opacity-25': processingDelete }"
	                    :disabled="processingDelete"
	                    v-on:click.prevent="removeData"
	                >
	                    {{ 'Delete Service' }}
	                </DangerButton>
	            </template>
	        </ConfirmationModal>
		</div>
	</AppLayout>
</template>

<style>
@import '../../../../../css/custom/form.css';
</style>