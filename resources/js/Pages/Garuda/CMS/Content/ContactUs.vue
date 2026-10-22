<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PopUpMessage from '@/Components/PopUp/PopUpMessage.vue';
import ContactUsForm from './Partials/ContactUsForm.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
	contactUs: {
		type: Object,
		default: null,
	},
});

const actionStatus = ref(false), actionTitle = ref(''), actionMessage = ref(''), actionTitleColor = ref(''), actionMessageColor = ref(''), confirmingDeletion = ref(false), processingDelete = ref(false);

const closePopUp = () => {
	actionStatus.value = false;
	actionTitle.value = '';
	actionMessage.value = '';
	actionTitleColor.value = '';
	actionMessageColor.value = '';

	location.reload();
};

const confirmDeletion = () => {
	confirmingDeletion.value = true;
};

const closeModalDelete = () => {
    confirmingDeletion.value = false;
};

const removeData = () => {
	processingDelete.value = true;

	axios.post(route("contact_us.delete", { contactUs: props.contactUs.data.id }), { _method: 'DELETE' }).then((response) => {
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
	<AppLayout title="Contact Us">
		<template #header>
			<h1 class="font-semibold text-xl text-gray-800 leading-tight">
				Contact Us
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

			<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
				<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
					<div>
        				<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        					<div v-if="!contactUs">
								<ContactUsForm title="Create Contact Us Page" @success="successFormSubmit">
									<PrimaryButton>Add</PrimaryButton>
								</ContactUsForm>
							</div>

							<div v-else>
								<div class="mb-10 flex gap-4">
									<div>
										<ContactUsForm title="Edit Home Page" :id="contactUs.data.id" @success="successFormSubmit">
											<PrimaryButton>Edit</PrimaryButton>
										</ContactUsForm>
									</div>
									<div>
										<DangerButton v-on:click.prevent="confirmDeletion">Delete</DangerButton>
									</div>
								</div>

								<div class="grid grid-cols-2 gap-2">
									<div>
										<div class="flex gap-2">
											<div>To Email</div>
											<div>: {{ contactUs.data.to_email }}</div>
										</div>
									</div>
									<div>
										<div class="flex gap-2">
											<div>Whatsapp Number</div>
											<div>: {{ contactUs.data.whatsapp_number }}</div>
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
	                {{ 'Delete Contact Us' }}
	            </template>

	            <template #content>
	                {{ 'Are you sure want to delete all contact us content?' }}
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
	                    {{ 'Delete Contact Us' }}
	                </DangerButton>
	            </template>
	        </ConfirmationModal>
		</div>
	</AppLayout>
</template>

<style>
@import '../../../../../css/custom/form.css';
</style>