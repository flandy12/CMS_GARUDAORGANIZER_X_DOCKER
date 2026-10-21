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
	<AppLayout title="Kontak">
		<template #header>
			<h1 class="font-semibold text-xl text-gray-800 leading-tight">
				Contact Us
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

			<div class="mx-auto max-w-5xl">
				<div class="cms-page-heading">
					<div><span class="cms-eyebrow">Informasi publik</span><h2>Kontak Perusahaan</h2><p>Pastikan email dan nomor WhatsApp selalu aktif agar pengunjung mudah menghubungi tim.</p></div>
					<span class="cms-status"><span></span>{{ contactUs ? 'Kontak aktif' : 'Belum tersedia' }}</span>
				</div>
				<div class="cms-panel mt-6">
					<div>
						<div class="p-4 sm:p-6 lg:p-8">
							<div v-if="!contactUs" class="cms-empty-state">
								<div class="cms-empty-icon">+</div><h3>Informasi kontak belum tersedia</h3><p>Tambahkan email dan nomor WhatsApp yang dapat dihubungi.</p>
								<ContactUsForm v-if="$page.props.permissions.includes('create contact us')" title="Tambah Informasi Kontak" @success="successFormSubmit">
									<PrimaryButton>Tambah Kontak</PrimaryButton>
								</ContactUsForm>
								<p v-else class="text-sm text-gray-500">Belum ada konten. Anda tidak memiliki akses untuk membuat konten baru.</p>
							</div>

							<div v-else>
								<div class="cms-actions">
									<div v-if="$page.props.permissions.includes('update contact us')">
										<ContactUsForm title="Ubah Informasi Kontak" :id="contactUs.data.id" @success="successFormSubmit">
											<PrimaryButton>Ubah Kontak</PrimaryButton>
										</ContactUsForm>
									</div>
									<div v-if="$page.props.permissions.includes('delete contact us')">
										<DangerButton v-on:click.prevent="confirmDeletion">Hapus</DangerButton>
									</div>
								</div>

								<div class="grid grid-cols-2 gap-4">
									<div>
										<div class="cms-contact-card">
											<div class="cms-contact-icon">@</div>
											<div><span>Email tujuan</span><strong>{{ contactUs.data.to_email }}</strong></div>
										</div>
									</div>
									<div>
										<div class="cms-contact-card">
											<div class="cms-contact-icon">WA</div>
											<div><span>Nomor WhatsApp</span><strong>{{ contactUs.data.whatsapp_number }}</strong></div>
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
	                {{ 'Apakah Anda yakin ingin menghapus informasi kontak?' }}
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
	                    {{ 'Hapus Kontak' }}
	                </DangerButton>
	            </template>
	        </ConfirmationModal>
		</div>
	</AppLayout>
</template>

<style>
@import '../../../../../css/custom/form.css';
</style>
