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
	banner: '',
	banner_wording: '',
	wording: '',
	image: '',
	vision_mission: '',
	errors: [],
});

const form_is_open = ref(false), processing = ref(false), tinyMCEAPI = ref(import.meta.env.VITE_TINYMCE_API_KEY), banner_cropper = ref(null), temp_banner = ref (null), banner_width = ref(1200), banner_height = ref(400), banner_source = ref(null), errorResponse = ref('');

const openForm = () => {
	form_is_open.value = true;

	if(props.id) {
		axios.get(route('about_us.edit', { aboutUs: props.id })).then((response) => {
			const { banner, banner_wording, wording, image, vision_mission } = response.data.results;

			form.banner = banner;
			form.banner_wording = banner_wording;
			form.wording = wording;
			form.image = image;
			form.vision_mission = vision_mission;
		}).catch((error) => {
			alert("Something when wrong. Please try again");
		});
	}
};

const closeForm = () => {
	form_is_open.value = false;

	form.banner = '';
	form.banner_wording = '';
	form.wording = '';
	form.image = '';
	form.vision_mission = '';
	form.errors = [];
	banner_source.value = null;
	banner_source.value = null;
	banner_cropper.value = null;
	processing.value = false;
	errorResponse.value = '';
};

const submitForm = () => {
	processing.value = true;

	form.errors = [];

	errorResponse.value = "";

	form.wording = tinymce.get('wording').getContent();

	let uri = route("about_us.store");

	const formData = getFormData(form);

	if(props.id) {
		formData.append("_method", "PUT");

		uri = route("about_us.update", { aboutUs: props.id });
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
};

const selectBanner = (event) => {
	const { files } = event.currentTarget;

	if(files.length > 0) {
		temp_banner.value = URL.createObjectURL(files[0]);
		
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
		
		form.banner = myFile;
		temp_banner.value = null;
		banner_source.value = URL.createObjectURL(blob);

	}, 'image/png', 0.55);
};

const selectImage = (event) => {
	form.image = event.currentTarget.files[0];
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
					<div>
						<InputLabel value="Banner *" />

						<TextInput 
							type="file"
							accept=".jpeg,.jpg,.png"
							class="w-full mt-2"
							id="file-input"
							v-on:change="selectBanner"
						/>

						<div v-if="temp_banner">
							<vue-cropper 
								ref="banner_cropper"
								:src="temp_banner"
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

							<PrimaryButton v-on:click.prevent="cropBannerImage(key)" class="mt-2">Crop</PrimaryButton>
						</div>

						<div class="mt-2" v-if="banner_source">
							<img :src="banner_source" class="w-72">
						</div>

						<div class="mt-2" v-if="!banner_source && props.id">
							<img :src="'/'+form.banner" class="w-72">
						</div>

						<div v-for="(error, key) in form.errors.banner" :key="key">
							<InputError :message="error" class="mt-2" />
						</div>
					</div>

					<div class="mt-4">
						<InputLabel value="Banner Wording *" />

						<textarea class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-2" v-model="form.banner_wording"></textarea>

						<div v-for="(error, key) in form.errors.banner_wording" :key="key">
							<InputError :message="error" class="mt-2" />
						</div>
					</div>

					<div class="mt-4">
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
								:id="'wording'"
								:name="'wording'"
						/>

						<div v-for="(error, key) in form.errors.wording" :key="key">
							<InputError :message="error" class="mt-2" />
						</div>
					</div>

					<div class="mt-4">
						<InputLabel value="Image *" />

						<TextInput 
							type="file"
							accept=".jpg,.jpeg,.png"
							v-on:change="selectImage"
						/>

						<div v-if="props.id" class="mt-2">
							<div>
								<img :src="form.image" class="w-72">
							</div>
						</div>

						<div v-for="(error, key) in form.errors.image" :key="key">
							<InputError :message="error" class="mt-2" />
						</div>
					</div>

					<div class="mt-4">
						<InputLabel value="Vision and Mission *" />

						<textarea class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-2" v-model="form.vision_mission"></textarea>

						<div v-for="(error, key) in form.errors.vision_mission" :key="key">
							<InputError :message="error" class="mt-2" />
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