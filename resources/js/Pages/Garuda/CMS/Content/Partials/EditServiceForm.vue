<script setup>
import { ref, reactive } from 'vue';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Editor from '@tinymce/tinymce-vue';
import VueCropper from '@ballcat/vue-cropper';
import 'cropperjs/dist/cropper.css';
import PlusIco from '@/Components/Icons/Plus.vue';
import RemoveIco from '@/Components/Icons/Close.vue';
import { validationErrors, getFormData } from '@/Library/helpers.js';

const emit = defineEmits(["success"]);

const props = defineProps({
	title: String,
	id: {
		type: String,
		default: null,
	}
});

const form = reactive({
	type: '',
	banner: '',
	wording: '',
	service_description: '',
	errors: [],
});

const form_is_open = ref(false), processing = ref(false), tinyMCEAPI = ref(import.meta.env.VITE_TINYMCE_API_KEY), banner_cropper = ref(null), temp_banners = ref(null), banner_width = ref(1200), banner_height = ref(400), banner_sources = ref(null),  errorResponse = ref('');

const openForm = () => {
	form_is_open.value = true;

	if(props.id) {
		axios.get(route('service.edit', { service: props.id })).then((response) => {
			const { type, banner, wording, description } = response.data.results;

			form.type = type;
			form.banner = banner;
			form.wording = wording;
			form.service_description = description;
		}).catch((error) => {
			alert("Something when wrong. Please try again");
		});
	}
};

const closeForm = () => {
	form_is_open.value = false;

	form.type = '';
	form.banner = '';
	form.wording = '';
	form.service_description = '';
	form.errors = [];
	banner_sources.value = null;
	temp_banners.value = null;
	banner_cropper.value = null;
	processing.value = false;
	errorResponse.value = '';
};

const submitForm = () => {
	processing.value = true;
	
	form.errors = [];

	errorResponse.value = "";

	let uri = route("service.store");

	const formData = getFormData(form);

	if(props.id) {
		formData.append("_method", "PUT");

		uri = route("service.update", { service: props.id });
	}

	axios.post(uri, formData).then((response) => {
		processing.value = false;

		emit("success", response.data.title, response.data.message);

		closeForm();
	}).catch((error) => {
		processing.value = false;

		const { response } = error;
		const { status, data, statusText } = response;
		const { errors, message } = data;

		if(status === 422) {
			form.errors = validationErrors(errors);
		} else {
			errorResponse.value = message;
		}
	});
};

const finishLoadTinyMce = (event) => {
	event.on('init', (args) => {
		const value = form.wording;

		tinymce.get('wording').setContent(value ? value : '');
	});

	event.on('change', (args) => {
		const tinyMceContent = tinymce.get("wording").getContent();

		form.wording = tinyMceContent ? tinyMceContent : form.wording;
	});
};

const selectBanner = (event) => {
	const { files } = event.currentTarget;

	if(files.length > 0) {
		temp_banners.value = URL.createObjectURL(files[0]);
		
		setTimeout(() => {
			document.getElementById("file-input").value = "";

			banner_cropper.value.setCropBoxData({ height: banner_height.value, width: banner_width.value,  });
		}, 500);
	}
};

const cropBannerImage = () => {
	banner_cropper.value.getCroppedCanvas({
		width: banner_width.value,
  		height: banner_height.value,
	}).toBlob((blob) => {
		const myFile = new File([blob], 'image.png', {
		    type: blob.type,
		});
		
		banner_sources.value = URL.createObjectURL(blob);

		temp_banners.value = null;
		
		form.banner = myFile;
	}, 'image/png', 0.55);
};
</script>

<template>
	<span>
		<span v-on:click.prevent="openForm">
			<slot />
		</span>

		<DialogModal :show="form_is_open" @close="closeForm" :closeable="false" maxWidth="64rem">
			<template #title>
				{{ title }}
			</template>

			<template #content>
				<div>
					<div class="mt-4">
						<div class="w-full">
							<div>
								<InputLabel value="Type *" />
								
								<TextInput 
									type="text"
									accept=".jpeg,.jpg,.png"
									class="w-full mt-2"
									v-model="form.type"
								/>

								<div v-for="(error, errorKey) in form.errors.type" :key="errorKey">
									<InputError :message="error" class="mt-2" />
								</div>
							</div>

							<div class="mt-2">
								<InputLabel value="Banner *" />

								<TextInput 
									type="file"
									accept=".jpeg,.jpg,.png"
									class="w-full mt-2"
									id="file-input"
									v-on:change="selectBanner"
								/>

								<div v-if="temp_banners">
									<vue-cropper 
										ref="banner_cropper"
										:src="temp_banners"
										alt="Source Image"
										dragMode="move"
										:cropBoxResizable="false"
										:cropBoxMovable="false"
										:movable="true"
										:scalable="false"
										:toggleDragModeOnDblclick="false"
										:minContainerWidth="1300"
										:minContainerHeight="500"
										:minCropBoxWidth="banner_width"
										:minCropBoxHeight="banner_height"
									/>

									<PrimaryButton v-on:click.prevent="cropBannerImage" class="mt-2">Crop</PrimaryButton>
								</div>

								<div class="mt-2" v-if="banner_sources">
									<img :src="banner_sources" class="w-72">
								</div>

								<div class="mt-2" v-if="!banner_sources && props.id">
									<img :src="form.banner" class="w-72">
								</div>

								<div v-for="(error, errorKey) in form.errors.banner" :key="errorKey">
									<InputError :message="error" class="mt-2" />
								</div>
							</div>

							<InputLabel value="Banner Description *" class="mt-2" />

							<textarea v-model="form.service_description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-2"></textarea>

							<div v-if="form.errors.service_description" v-for="(error, errorKey) in form.errors.service_description" :key="errorKey">
								<InputError :message="error" class="mt-2" />
							</div>

							<div class="mt-2">
								<InputLabel value="Wording *" />

								<Editor
									:api-key="tinyMCEAPI"
									:init="{
											setup: finishLoadTinyMce,
											selector: '#wording',
											plugins: 'lists wordcount',
			  								toolbar: 'undo redo | blocks fontsize | bold italic underline strikethrough | forecolor backcolor | numlist bullist indent outdent | align lineheight',
											height: 300,
											menubar: false,
											image_title: false,
											image_advtab: false,
											automatic_uploads: false,
											media_live_embeds: false,
											toolbar_location: 'top',
										    skin: 'outside',
										    forced_root_block: 'p',
							                relative_urls: false,
							                remove_script_host : false,
									}"
									id="wording"
									name="wording"
								/>

								<div v-for="(error, errorKey) in form.errors.wording" :key="errorKey">
									<InputError :message="error" class="mt-2" />
								</div>
							</div>
						</div>
					</div>

					<div class="mt-2" v-show="errorResponse">
		            	<InputError :message="errorResponse" class="mt-2 mr-3" />
		            </div>
				</div>
			</template>

			<template #footer>
				<SecondaryButton v-on:click.prevent="closeForm">
					Cancel
				</SecondaryButton>

				<PrimaryButton
					class="ml-3"
	                :class="{ 'opacity-25': processing }"
	                :disabled="processing"
	                v-on:click.prevent="submitForm"
				>
					Submit
				</PrimaryButton>
			</template>
		</DialogModal>
	</span>
</template>