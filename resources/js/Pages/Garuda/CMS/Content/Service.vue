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
	<AppLayout title="Layanan">
		<template #header>
			<h1 class="font-semibold text-xl text-gray-800 leading-tight">
				Service
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
					<div><span class="cms-eyebrow">Konten website</span><h2>Daftar Layanan</h2><p>Kelola informasi layanan, gambar pendukung, dan deskripsi yang dilihat pengunjung.</p></div>
					<span class="cms-status"><span></span>{{ services?.data?.length || 0 }} layanan aktif</span>
				</div>
				<div class="cms-panel mt-6">
					<div>
						<div class="p-4 sm:p-6 lg:p-8">
							<div class="cms-actions">
							<ServiceForm v-if="$page.props.permissions.includes('create service')" title="Tambah Layanan" @success="successFormSubmit">
									<PrimaryButton>Tambah Layanan</PrimaryButton>
								</ServiceForm>
							</div>

							<div v-if="services">
								<div class="cms-entry-card mt-6 grid grid-cols-2 gap-6" v-for="(result, key) in services.data" :key="key">
									<div>
										<div>
											<img :src="result.banner" class="w-full">
										</div>
										<div class="mt-2">
											<span v-html="result.wording"></span>
										</div>
									</div>
									<div class="flex flex-col gap-4 sm:flex-row sm:justify-between">
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
											<ServiceForm v-if="$page.props.permissions.includes('update service')" title="Ubah Layanan" :id="result.id" @success="successFormSubmit">
													<PrimaryButton>Ubah</PrimaryButton>
												</ServiceForm>
											</div>

										<div v-if="$page.props.permissions.includes('delete service')" class="mt-4">
												<DangerButton v-on:click.prevent="confirmDeletion(result.id, result.type)">Hapus</DangerButton>
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
	                {{ 'Apakah Anda yakin ingin menghapus layanan '+deleteLabel+'?' }}
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
	                    {{ 'Hapus Layanan' }}
	                </DangerButton>
	            </template>
	        </ConfirmationModal>
		</div>
	</AppLayout>
</template>

<style>
@import '../../../../../css/custom/form.css';
</style>
