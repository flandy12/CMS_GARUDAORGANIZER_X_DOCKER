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
	<AppLayout title="Proyek">
		<template #header>
			<h1 class="font-semibold text-xl text-gray-800 leading-tight">
				Project
			</h1>
		</template>

		<div class="cms-content px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
			<PopUpMessage
				v-if="actionStatus" 
				:title="actionTitle"
				:message="actionMessage"
				:actionTitleColor="actionTitleColor"
				:actionMessageColor="actionMessageColor"
				@closePopUp="closePopUp"
			/>

			<div class="mx-auto max-w-7xl">
				<div class="cms-page-heading">
					<div><span class="cms-eyebrow">Konten website</span><h2>Halaman Proyek</h2><p>Kelola banner, narasi, dan portofolio proyek perusahaan dalam satu halaman.</p></div>
					<span class="cms-status"><span></span>{{ project ? 'Konten aktif' : 'Belum tersedia' }}</span>
				</div>
				<div class="cms-panel mt-6">
					<div>
						<div class="p-4 sm:p-6 lg:p-8">
							<div v-if="!project" class="cms-empty-state">
								<div class="cms-empty-icon">+</div><h3>Konten proyek belum tersedia</h3><p>Tambahkan portofolio proyek pertama untuk mulai menampilkan karya.</p>
								<ProjectForm v-if="$page.props.permissions.includes('create project')" title="Tambah Konten Proyek" @success="successFormSubmit">
									<PrimaryButton>Tambah Konten</PrimaryButton>
								</ProjectForm>
								<p v-else class="text-sm text-gray-500">Belum ada konten. Anda tidak memiliki akses untuk membuat konten baru.</p>
							</div>

							<div v-else>
								<div class="cms-actions">
									<div v-if="$page.props.permissions.includes('update project')">
										<ProjectForm title="Ubah Konten Proyek" :id="project.data.id" @success="successFormSubmit">
											<PrimaryButton>Ubah Konten</PrimaryButton>
										</ProjectForm>
									</div>
									<div v-if="$page.props.permissions.includes('delete project')">
										<DangerButton v-on:click.prevent="confirmDeletion">Hapus</DangerButton>
									</div>
								</div>

								<div class="cms-entry-card grid grid-cols-2 gap-6">
									<div>
										<div>
											<img :src="project.data.banner" class="w-full">
										</div>
									</div>
									<div>
										<span v-html="project.data.wording"></span>
									</div>
								</div>

								<div class="cms-entry-card mt-6 grid grid-cols-2 gap-6" v-for="(result, key) in project.data.results" :key="key">
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
	                {{ 'Apakah Anda yakin ingin menghapus seluruh konten proyek?' }}
	            </template>

	            <template #footer>
	                <SecondaryButton v-on:click.prevent="closeModalDelete">
	                    {{ 'Batal' }}
	                </SecondaryButton>

	                <DangerButton
	                    class="ml-3"
	                    :class="{ 'opacity-25': processingDelete }"
	                    :disabled="processingDelete"
	                    v-on:click.prevent="removeData"
	                >
	                    {{ 'Hapus Proyek' }}
	                </DangerButton>
	            </template>
	        </ConfirmationModal>
		</div>
	</AppLayout>
</template>

<style>
@import '../../../../../css/custom/form.css';
</style>
