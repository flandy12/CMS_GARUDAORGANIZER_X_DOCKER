<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import HomeForm from './Partials/HomeForm.vue';
import Circle from '@/Components/Icons/Circle.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import PopUpMessage from '@/Components/PopUp/PopUpMessage.vue';

const props = defineProps({
	home: {
		type: Object,
		default: null,
	},
});

const confirmingDeletion = ref(false), processingDelete = ref(false), actionStatus = ref(false), actionTitle = ref(''), actionMessage = ref(''), actionTitleColor = ref(""), actionMessageColor = ref("");

const confirmDeletion = () => {
	confirmingDeletion.value = true;
};

const closeModalDelete = () => {
    confirmingDeletion.value = false;
};

const removeData = () => {
	processingDelete.value = true;

	axios.post(route("home.delete", { home: props.home.data.id }), { _method: 'DELETE' }).then((response) => {
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

const closePopUp = () => {
	actionStatus.value = false;
	actionTitle.value = '';
	actionMessage.value = '';
	actionTitleColor.value = '';
	actionMessageColor.value = '';

	location.reload();
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
	<AppLayout title="Beranda">
		<template #header>
			<h1 class="font-semibold text-xl text-gray-800 leading-tight">
				Home
			</h1>
		</template>

		<div class="cms-content cms-home px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
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
					<div><span class="cms-eyebrow">Konten website</span><h2>Halaman Beranda</h2><p>Atur banner, layanan, sektor, proyek, pencapaian, testimoni, dan partner yang tampil di halaman utama.</p></div>
					<span class="cms-status"><span></span>{{ home ? 'Konten aktif' : 'Belum tersedia' }}</span>
				</div>
				<div class="cms-panel mt-6">
					<div>
						<div class="p-4 sm:p-6 lg:p-8">
							<div v-if="!home" class="cms-empty-state">
								<div class="cms-empty-icon">+</div><h3>Konten beranda belum tersedia</h3><p>Tambahkan konten pertama agar halaman utama dapat ditampilkan.</p>
								<HomeForm v-if="$page.props.permissions.includes('create home')" title="Tambah Konten Beranda" @success="successFormSubmit">
									<PrimaryButton>Tambah Konten</PrimaryButton>
								</HomeForm>
								<p v-else class="text-sm text-gray-500">Belum ada konten. Anda tidak memiliki akses untuk membuat konten baru.</p>
							</div>

							<div v-else>
								<div class="cms-actions">
									<div v-if="$page.props.permissions.includes('update home')">
										<HomeForm title="Ubah Konten Beranda" :id="home.data.id" @success="successFormSubmit">
											<PrimaryButton>Ubah Konten</PrimaryButton>
										</HomeForm>
									</div>
									<div v-if="$page.props.permissions.includes('delete home')">
										<DangerButton v-on:click.prevent="confirmDeletion">Hapus</DangerButton>
									</div>
								</div>

								<div class="grid grid-cols-2 gap-4">
									<div>
										<div class="flex items-center gap-2">
											<Circle
												class="w-2 h-2"
												fill="gray"
											/>
											<h1 class="text-xl font-semibold underline">Banner</h1>
										</div>

										<div class="mt-4">
											<div>
												<ul>
													<li v-for="(banner, key) in home.data.banner" :key="key" class="mb-4">
														<div>
															<img :src="'/'+banner.image">
														</div>

														<div class="mt-2">
															<h2 class="font-semibold text-lg">{{ banner.title }}</h2>
														</div>

														<div class="mt-2">
															<span v-html="banner.description"></span>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>

									<div>
										<div class="flex items-center gap-2">
											<Circle
												class="w-2 h-2"
												fill="gray"
											/>
											<h1 class="text-xl font-semibold underline">Service</h1>
										</div>

										<div class="mt-4">
											<div>
												<ul>
													<li v-for="(service, key) in home.data.service" :key="key" class="mb-4">
														<a :href="service.link" target="_blank">
															<div>
																<img :src="'/'+service.image">
															</div>

															<div class="mt-2">
																<h2 class="font-semibold text-lg">{{ service.name }}</h2>
															</div>

															<div class="mt-2">
																<span v-html="service.description"></span>
															</div>
														</a>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>

								<div class="grid grid-cols-2 mt-10 gap-4">
									<div>
										<div class="flex items-center gap-2">
											<Circle
												class="w-2 h-2"
												fill="gray"
											/>
											<h1 class="text-xl font-semibold underline">Sector</h1>
										</div>

										<div class="mt-4">
											<div>
												<ul>
													<li v-for="(sector, key) in home.data.sector" :key="key" class="mb-4">
														<div>
															<img :src="'/'+sector.image">
														</div>

														<div class="mt-2">
															<h2 class="font-semibold text-lg">{{ sector.name }}</h2>
														</div>

														<div class="mt-2">
															<span v-html="sector.description"></span>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>

									<div>
										<div class="flex items-center gap-2">
											<Circle
												class="w-2 h-2"
												fill="gray"
											/>
											<h1 class="text-xl font-semibold underline">Partner</h1>
										</div>

										<div class="mt-4">
											<div>
												<ul class="flex gap-4">
													<li v-for="(partner, key) in home.data.partner" :key="key">
														<div>
															<img :src="'/'+partner.logo">
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>

								<div class="grid grid-cols-2 mt-10 gap-4">
									<div>
										<div class="flex items-center gap-2">
											<Circle
												class="w-2 h-2"
												fill="gray"
											/>
											<h1 class="text-xl font-semibold underline">Project</h1>
										</div>

										<div class="mt-4">
											<div>
												<ul>
													<li v-for="(project, key) in home.data.project" :key="key" class="mb-4">
														<div>
															<img :src="'/'+project.image">
														</div>

														<div class="mt-2">
															<h2 class="font-semibold text-lg">{{ project.name }}</h2>
														</div>

														<div class="mt-2">
															<span v-html="project.description"></span>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>

									<div>
										<div class="flex items-center gap-2">
											<Circle
												class="w-2 h-2"
												fill="gray"
											/>
											<h1 class="text-xl font-semibold underline">Achievement</h1>
										</div>

										<div class="mt-4">
											<div>
												<ul>
													<li v-for="(achievement, key) in home.data.achievement" :key="key" class="mb-4">
														<div>
															<img :src="'/'+achievement.image">
														</div>

														<div class="mt-2">
															<h2 class="font-semibold text-lg">{{ achievement.name }}</h2>
														</div>

														<div class="mt-2">
															<span v-html="achievement.number"></span>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>

								<div class="grid grid-cols-2 mt-10 gap-4">
									<div>
										<div class="flex items-center gap-2">
											<Circle
												class="w-2 h-2"
												fill="gray"
											/>
											<h1 class="text-xl font-semibold underline">Testimonial</h1>
										</div>

										<div class="mt-4">
											<div>
												<ul>
													<li v-for="(testimonial, key) in home.data.testimonial" :key="key" class="mb-4">
														<div>
															<img :src="'/'+testimonial.image">
														</div>

														<div class="mt-2">
															<h2 class="font-semibold text-lg">{{ testimonial.title }}</h2>
														</div>

														<div class="mt-2">
															<iframe width="auto" height="227px" allowFullScreen allow='autoplay' :src="testimonial.video" :title="testimonial.title"></iframe>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>

									<div>
										<div class="flex items-center gap-2">
											<Circle
												class="w-2 h-2"
												fill="gray"
											/>
											<h1 class="text-xl font-semibold underline">Why Choose Us</h1>
										</div>

										<div class="mt-4">
											<div class="gap-4 flex">
												<div v-for="(other_image, key) in home.data.why_choose_us_other_images" :key="key">
													<img :src="other_image" />
												</div>
											</div>
										</div>

										<div class="mt-4">
											<div>
												<ul>
													<li v-for="(why_choose_us, key) in home.data.why_choose_us" :key="key">
														<div>
															<img :src="'/'+why_choose_us.image" class="w-32">
														</div>

														<div class="mt-2">
															<span v-html="why_choose_us.description"></span>
														</div>
													</li>
												</ul>
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
	                {{ 'Delete Home' }}
	            </template>

	            <template #content>
	                {{ 'Apakah Anda yakin ingin menghapus seluruh konten beranda?' }}
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
	                    {{ 'Hapus Beranda' }}
	                </DangerButton>
	            </template>
	        </ConfirmationModal>
		</div>
	</AppLayout>
</template>

<style>
@import '../../../../../css/custom/form.css';
</style>
