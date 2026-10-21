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
import { validationErrors, getFormData, validateImageIsExist, alertNotHaveImage } from '@/Library/helpers.js';

const emit = defineEmits(["success"]);

const props = defineProps({
	title: String,
	id: {
		type: String,
		default: null,
	}
});

const form = reactive({
	type: [],
	banner: [],
	wording: [],
	service_descriptions: [],
	errors: [],
});

const form_is_open = ref(false), processing = ref(false), tinyMCEAPI = ref(import.meta.env.VITE_TINYMCE_API_KEY), banner_cropper = ref(null), temp_banners = ref([]), banner_width = ref(1200), banner_height = ref(400), banner_sources = ref([]), counter_services = ref(1), errorResponse = ref('');

const openForm = () => {
	form_is_open.value = true;

	if(props.id) {
		axios.get(route('service.edit', { service: props.id })).then((response) => {
			const { type, banner, wording, description } = response.data.results;

			counter_services.value = results.length;

			pushFormValue(results, ["type", "banner", "wording", "description"], ["type", "banner", "wording", "service_descriptions"]);
		}).catch((error) => {
			alert("Something when wrong. Please try again");
		});
	}
};

const pushFormValue = (results, fields, form_fields) => {
	if(results) {
		results.map((result, key) => {
			for(let i = 0; i < form_fields.length; i++) {
				form[form_fields[i]].push(result[fields[i]]);
			}
		});
	}
};

const closeForm = () => {
	form_is_open.value = false;

	form.type = [];
	form.banner = [];
	form.wording = [];
	form.service_descriptions = [];
	form.errors = [];
	counter_services.value = 1;
	banner_sources.value = [];
	temp_banners.value = [];
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

const finishLoadTinyMce = (event, key) => {
	event.on('init', (args) => {
		const value = form.wording[key];

		tinymce.get('wording_'+key).setContent(value ? value : '');
	});

	event.on('change', (args) => {
		form.wording[key] = tinymce.get('wording_'+key).getContent();
	});
};

const selectBanner = (event) => {
	if(event.currentTarget.files.length > 0) {
		const attr_id = event.currentTarget.getAttribute('id'), current_key = parseInt(event.currentTarget.getAttribute("data-current-key")), not_have_images = validateImageIsExist(current_key, form.banner);

		if(not_have_images.length < 1 ) {
			const img = URL.createObjectURL(event.currentTarget.files[0]);

			if(!temp_banners.value[current_key]) {
				temp_banners.value.push(img);
			} else {
				temp_banners.value[current_key] = img;
			}

			setTimeout(() => {
				document.getElementById(attr_id).value = "";

				if(banner_cropper.value) {
					banner_cropper.value[0].setCropBoxData({ height: banner_height.value, width: banner_width.value,  });
				}
			}, 500);
		} else {
			alertNotHaveImage(not_have_images, "Banner");
		}
	}
};

const cropBannerImage = (key) => {
	banner_cropper.value[0].getCroppedCanvas({
		width: banner_width.value,
  		height: banner_height.value,
	}).toBlob((blob) => {
		const myFile = new File([blob], 'image.png', {
		    type: blob.type,
		});
		
		banner_sources.value.push(URL.createObjectURL(blob));

		temp_banners.value[key] = null;
		
		form.banner.push(myFile);
	}, 'image/png', 0.55);
};

const addService = (event) => {
	counter_services.value += 1;
};

const deleteService = (event) => {
	counter_services.value -= 1;
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
						<fieldset>
							<legend>
								<span class="font-bold">Services:</span>
								<PlusIco class="inline-block ml-2 h-6 stroke-gray-500 hover:cursor-pointer" v-on:click.prevent="addService" />
							</legend>

							<div class="mt-4" v-for="(number, key) in counter_services" :key="key">
								<div class="flex gap-2 w-full">
									<div>
										<div class="font-bold text-center">{{ number+'.' }}</div>
										<div class="mt-2" v-if="key > 0">
											<RemoveIco width="25" height="25" class="hover:cursor-pointer" :title="'Delete Service '+number" v-on:click.prevent="deleteService" />
										</div>
									</div>

									<div class="w-full">
										<div>
											<InputLabel value="Type *" />
											
											<TextInput 
												type="text"
												accept=".jpeg,.jpg,.png"
												class="w-full mt-2"
												v-model="form.type[key]"
											/>
										</div>

										<div class="mt-2">
											<InputLabel value="Banner *" />

											<TextInput 
												type="file"
												accept=".jpeg,.jpg,.png"
												class="w-full mt-2"
												:id="'file-input-'+key"
												v-on:change="selectBanner"
												:data-current-key="key"
											/>

											<div v-if="temp_banners[key]">
												<vue-cropper 
													ref="banner_cropper"
													:src="temp_banners[key]"
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

											<div class="mt-2" v-if="banner_sources[key]">
												<img :src="banner_sources[key]" class="w-72">
											</div>

											<div class="mt-2" v-if="!banner_sources[key] && props.id">
												<img :src="form.banner[key]" class="w-72">
											</div>
										</div>

										<InputLabel value="Banner Description *" class="mt-2" />

										<textarea v-model="form.service_descriptions[key]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-2"></textarea>

										<div class="mt-2">
											<InputLabel value="Wording *" />

											<Editor
												:api-key="tinyMCEAPI"
												:init="{
														setup: (event) => finishLoadTinyMce(event, key),
														selector: '#wording_'+key,
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
												:id="'wording_'+key"
												:name="'wording_'+key"
											/>
										</div>
									</div>
								</div>

								<div v-for="(error, errorKey) in form.errors.type" :key="errorKey">
									<InputError :message="error" class="mt-2" />
								</div>

								<div v-for="(error, errorKey) in form.errors.banner" :key="errorKey">
									<InputError :message="error" class="mt-2" />
								</div>

								<div v-for="(error, errorKey) in form.errors.wording" :key="errorKey">
									<InputError :message="error" class="mt-2" />
								</div>

								<div v-if="form.errors.service_descriptions" v-for="(error, errorKey) in form.errors.service_descriptions" :key="errorKey">
									<InputError :message="error" class="mt-2" />
								</div>
							</div>
						</fieldset>
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