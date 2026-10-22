<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PopUpMessage from '@/Components/PopUp/PopUpMessage.vue';
import ProjectForm from './Partials/ProjectForm.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
	project: {
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

	axios.post(route("project.delete", { project: props.project.data.id }), { _method: 'DELETE' }).then((response) => {
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
	<AppLayout title="Project">
		<template #header>
			<h1 class="font-semibold text-xl text-gray-800 leading-tight">
				Project
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
        					<div v-if="!project">
								<ProjectForm title="Create Project Page" @success="successFormSubmit">
									<PrimaryButton>Add</PrimaryButton>
								</ProjectForm>
							</div>

							<div v-else>
								<div class="mb-10 flex gap-4">
									<div>
										<ProjectForm title="Edit Project Page" :id="project.data.id" @success="successFormSubmit">
											<PrimaryButton>Edit</PrimaryButton>
										</ProjectForm>
									</div>
									<div>
										<DangerButton v-on:click.prevent="confirmDeletion">Delete</DangerButton>
									</div>
								</div>

								<div class="grid grid-cols-2 gap-2">
									<div>
										<div>
											<img :src="project.data.banner" class="w-full">
										</div>
									</div>
									<div>
										<span v-html="project.data.wording"></span>
									</div>
								</div>

								<div class="grid grid-cols-2 gap-2 mt-10" v-for="(result, key) in project.data.results" :key="key">
									<div>
										<div>
											<img :src="'/'+result.image" class="w-full">
										</div>
									</div>
									<div>
										<h1 class="text-center mb-4 font-semibold">{{ result.title }}</h1>
										
										<span v-html="result.description"></span>
									</div>
								</div>
							</div>
        				</div>
        			</div>
        		</div>
        	</div>

        	<ConfirmationModal :show="confirmingDeletion" @close="closeModalDelete">
	            <template #title>
	                {{ 'Delete Project' }}
	            </template>

	            <template #content>
	                {{ 'Are you sure want to delete all project content?' }}
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
	                    {{ 'Delete Project' }}
	                </DangerButton>
	            </template>
	        </ConfirmationModal>
		</div>
	</AppLayout>
</template>

<style>
@import '../../../../../css/custom/form.css';
</style>