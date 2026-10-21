<script setup>
import { ref, reactive } from 'vue';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PlusIco from '@/Components/Icons/Plus.vue';
import RemoveIco from '@/Components/Icons/Close.vue';
import Editor from '@tinymce/tinymce-vue';
import VueCropper from '@ballcat/vue-cropper';
import 'cropperjs/dist/cropper.css';
import { ColorPicker } from "vue3-colorpicker";
import "vue3-colorpicker/style.css";
import { validationErrors, getFormData, validateImageIsExist, alertNotHaveImage } from '@/Library/helpers.js';

const emit = defineEmits(["success"]);

const props = defineProps({
	title: String,
	id: {
		type: String,
		default: null
	}
});

const form = reactive({
	banner_images: [],
	banner_titles: [],
	banner_descriptions: [],
	banner_colors: ["gray"],
	service_name: [],
	service_links: [],
	service_descriptions: [],
	service_images: [],
	sector_name: [],
	sector_descriptions: [],
	sector_images: [],
	partner_logo: [],
	project_name: [],
	project_descriptions: [],
	project_images: [],
	achievement_images: [],
	achievement_numbers: [],
	achievement_name: [],
	testimonial_titles: [],
	testimonial_images: [],
	testimonial_videos: [],
	why_choose_us_descriptions: [],
	why_choose_us_images: [],
	why_choose_us_other_images: [],
	errors: [],
});

const form_is_open = ref(false), processing = ref(false), counter_banners = ref(1), counter_services = ref(1), counter_sectors = ref(1), counter_projects = ref(1), counter_achievements = ref(1), counter_testimonials = ref(1), counter_partners = ref(1), counter_why_choose_us = ref(1), tinyMCEAPI = ref(import.meta.env.VITE_TINYMCE_API_KEY), banner_cropper = ref(null), banner_sources = ref([]), banner_width = ref(600), banner_height = ref(400), temp_banners = ref([]), gradientColor = ref("linear-gradient(0deg, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 1) 100%)"), errorResponse = ref('');

const openForm = () => {
	form_is_open.value = true;

	if(props.id) {
		axios.get(route("home.edit", { home: props.id })).then((response) => {
			const { data } = response;
			const { results } = data;
			const { achievement, banner, partner, project, sector, service, testimonial, why_choose_us, why_choose_us_other_images } = results;

			counter_achievements.value = achievement.length;

			pushFormValue(achievement, ["image", "name", "number"], ["achievement_images", "achievement_name", "achievement_numbers"]);

			counter_banners.value = banner.length;

			pushFormValue(banner, ["description", "image", "title", "background_color"], ["banner_descriptions", "banner_images", "banner_titles", "banner_colors"]);

			counter_partners.value = partner.length;

			pushFormValue(partner, ["logo"], ["partner_logo"]);

			counter_projects.value = project.length;

			pushFormValue(project, ["description", "image", "name"], ["project_descriptions", "project_images", "project_name"]);

			counter_sectors.value = sector.length;

			pushFormValue(sector, ["description", "image", "name"], ["sector_descriptions", "sector_images", "sector_name"]);

			counter_services.value = service.length;

			pushFormValue(service, ["description", "link", "image", "name"], ["service_descriptions", "service_links", "service_images", "service_name"]);

			counter_testimonials.value = testimonial.length;

			pushFormValue(testimonial, ["image", "title", "video"], ["testimonial_images", "testimonial_titles", "testimonial_videos"]);

			counter_why_choose_us.value = why_choose_us ? why_choose_us.length : 1;

			pushFormValue(why_choose_us, ["description", "image"], ["why_choose_us_descriptions", "why_choose_us_images"]);

			for(let i = 0; i < why_choose_us_other_images.length; i++) {
				form.why_choose_us_other_images.push(why_choose_us_other_images[i]);
			}
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
	form.banner_images = [];
	form.banner_titles = [];
	form.banner_descriptions = [];
	form.banner_colors = [];
	form.service_name = [];
	form.service_links = [];
	form.service_descriptions = [];
	form.service_images = [];
	form.sector_name = [];
	form.sector_descriptions = [];
	form.sector_images = [];
	form.partner_logo = [];
	form.project_name = [];
	form.project_descriptions = [];
	form.project_images = [];
	form.achievement_images = [];
	form.achievement_name = [];
	form.achievement_numbers = [];
	form.testimonial_videos = [];
	form.testimonial_images = [];
	form.testimonial_titles = [];
	form.why_choose_us_descriptions = [];
	form.why_choose_us_images = [];
	form.why_choose_us_other_images = [];
	counter_banners.value = 0;
	counter_services.value = 0;
	counter_sectors.value = 0;
	counter_projects.value = 0;
	counter_achievements.value = 0;
	counter_testimonials.value = 0;
	counter_partners.value = 0;
	banner_cropper.value = null;
	temp_banners.value = [];
	processing.value = false;
	errorResponse.value = '';
};

const submitForm = () => {
	processing.value = true;

	form.errors = [];

	errorResponse.value = "";

	let uri = route('home.store');

	const formData = getFormData(form);

	if(props.id) {
		formData.append("_method", "PUT");
		
		uri = route('home.update', { home: props.id });
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

const addBanner = () => {
	counter_banners.value += 1;
};

const deleteBanner = (key) => {
	counter_banners.value -= 1;

	form.banner_images.splice(key, 1);
	form.banner_descriptions.splice(key, 1);
	form.banner_titles.splice(key, 1);
	form.banner_colors.splice(key, 1);
};

const addService = () => {
	counter_services.value += 1;
};

const deleteService = (key) => {
	counter_services.value -= 1;

	form.service_images.splice(key, 1);
	form.service_descriptions.splice(key, 1);
	form.service_name.splice(key, 1);
	form.service_links.splice(key, 1);
};

const addSector = () => {
	counter_sectors.value += 1;
};

const deleteSector = (key) => {
	counter_sectors.value -= 1;

	form.sector_images.splice(key, 1);
	form.sector_descriptions.splice(key, 1);
	form.sector_name.splice(key, 1);
};

const addProject = () => {
	counter_projects.value += 1;
};

const deleteProject = (key) => {
	counter_projects.value -= 1;

	form.project_images.splice(key, 1);
	form.project_descriptions.splice(key, 1);
	form.project_name.splice(key, 1);
};

const addAchievement = () => {
	counter_achievements.value += 1;
};

const deleteAchievement = (key) => {
	counter_achievements.value -= 1;

	form.achievement_images.splice(key, 1);
	form.achievement_numbers.splice(key, 1);
	form.achievement_name.splice(key, 1);
};

const addWhyChooseUs = () => {
	counter_why_choose_us.value += 1;
};

const deleteWhyChooseUs = (key) => {
	counter_why_choose_us.value -= 1;

	form.why_choose_us_images.splice(key, 1);
	form.why_choose_us_descriptions.splice(key, 1);
};

const addTestimonial = () => {
	counter_testimonials.value += 1;
};

const deleteTestimonial = (key) => {
	counter_testimonials.value -= 1;

	form.testimonial_images.splice(key, 1);
	form.testimonial_titles.splice(key, 1);
	form.testimonial_videos.splice(key, 1);
};

const addPartner = () => {
	counter_partners.value += 1;
};

const deletePartner = (key) => {
	counter_partners.value -= 1;

	form.partner_logo.splice(key, 1);
};

const finishLoadTinyMce = (event, key) => {
	event.on('init', (args) => {
		const value = form.banner_descriptions[key];

		tinymce.get('banner_descriptions_'+key).setContent(value ? value : '');
	});

	event.on('change', (args) => {
		form.banner_descriptions[key] = tinymce.get('banner_descriptions_'+key).getContent();
	});
};

const selectBanner = (event) => {
	if(event.currentTarget.files.length > 0) {
		const attr_id = event.currentTarget.getAttribute('id'), current_key = parseInt(event.currentTarget.getAttribute("data-current-key")), not_have_images = validateImageIsExist(current_key, form.service_images);

		if(not_have_images.length < 1 ) {
			temp_banners.value.push(URL.createObjectURL(event.currentTarget.files[0]));

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
		
		form.banner_images.push(myFile);
	}, 'image/png', 0.55);
};

const setServiceImage = (event) => {
	const current_key = parseInt(event.currentTarget.getAttribute("data-current-key")), file = event.currentTarget.files[0], not_have_images = validateImageIsExist(current_key, form.service_images);

	if(not_have_images.length < 1 ) {
		if(typeof form.service_images[current_key] != 'undefined') {
			form.service_images[current_key] = file;
		} else {
			form.service_images.push(file);
		}
	} else {
		alertNotHaveImage(not_have_images, "Service", "service_image_"+current_key);
	}
};

const setSectorImage = (event) => {
	const current_key = parseInt(event.currentTarget.getAttribute("data-current-key")), file = event.currentTarget.files[0], not_have_images = validateImageIsExist(current_key, form.sector_images);

	if(not_have_images.length < 1) {
		if(typeof form.sector_images[current_key] != 'undefined') {
			form.sector_images[current_key] = file;
		} else {
			form.sector_images.push(file);
		}
	} else {
		alertNotHaveImage(not_have_images, "Sector", "sector_image_"+current_key);
	}
};

const setProjectImage = (event) => {
	const current_key = parseInt(event.currentTarget.getAttribute("data-current-key")), file = event.currentTarget.files[0], not_have_images = validateImageIsExist(current_key, form.project_images);

	if(not_have_images.length < 1) {
		if(typeof form.project_images[current_key] != 'undefined') {
			form.project_images[current_key] = file;
		} else {
			form.project_images.push(file);
		}
	} else {
		alertNotHaveImage(not_have_images, "Project", "project_image_"+current_key);
	}
};

const setAchievementImage = (event) => {
	const current_key = parseInt(event.currentTarget.getAttribute("data-current-key")), file = event.currentTarget.files[0], not_have_images = validateImageIsExist(current_key, form.achievement_images);

	if(not_have_images.length < 1) {
		if(typeof form.achievement_images[current_key] != 'undefined') {
			form.achievement_images[current_key] = file;
		} else {
			form.achievement_images.push(file);
		}
	} else {
		alertNotHaveImage(not_have_images, "Achievement", "achievement_image_"+current_key);
	}
};

const setWhyChooseUsImage = (event) => {
	const current_key = parseInt(event.currentTarget.getAttribute("data-current-key")), file = event.currentTarget.files[0], not_have_images = validateImageIsExist(current_key, form.why_choose_us_images);

	if(not_have_images.length < 1) {
		if(typeof form.why_choose_us_images[current_key] != 'undefined') {
			form.why_choose_us_images[current_key] = file;
		} else {
			form.why_choose_us_images.push(file);
		}
	} else {
		alertNotHaveImage(not_have_images, "Why Choose Us", "why_choose_us_image_"+current_key);
	}
};

const setTestimonialImage = (event) => {
	const current_key = parseInt(event.currentTarget.getAttribute("data-current-key")), file = event.currentTarget.files[0], not_have_images = validateImageIsExist(current_key, form.testimonial_images);

	if(not_have_images.length < 1) {
		if(typeof form.testimonial_images[current_key] != 'undefined') {
			form.testimonial_images[current_key] = file;
		} else {
			form.testimonial_images.push(file);
		}
	} else {
		alertNotHaveImage(not_have_images, "Testimonial", "testimonial_image_"+current_key);
	}
};

const setPartnerImage = (event) => {
	const current_key = parseInt(event.currentTarget.getAttribute("data-current-key")), file = event.currentTarget.files[0], not_have_images = validateImageIsExist(current_key, form.partner_logo);

	if(not_have_images.length < 1) {
		if(typeof form.partner_logo[current_key] != 'undefined') {
			
		} else {
			form.partner_logo.push(file);
		}
	} else {
		alertNotHaveImage(not_have_images, "Partner Logo", "partner_image_"+current_key);
	}
};

const whyChooseUsOtherImage = (event) => {
	const images = event.currentTarget.files;

	if(images.length > 7) {
		alert("Can't add image more than 7 images");
		
		document.getElementById(event.currentTarget.getAttribute("id")).value = "";
	} else {
		for(let i = 0; i < images.length; i++) {
			form.why_choose_us_other_images.push(images[i]);
		}
	}
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
					<fieldset>
						<legend>
							<span class="font-bold">Banner:</span>
							<PlusIco class="inline-block ml-2 h-6 stroke-gray-500 hover:cursor-pointer" v-on:click.prevent="addBanner" />
						</legend>

						<div class="mt-2" v-for="(number, key) in counter_banners" :key="key">
							<div class="flex gap-2 w-full">
								<div>
									<div class="font-bold text-center">{{ number+'.' }}</div>
									<div class="mt-2" v-if="key > 0">
										<RemoveIco width="25" height="25" class="hover:cursor-pointer" :title="'Delete Banner '+number" v-on:click.prevent="deleteBanner(key)" />
									</div>
								</div>

								<div class="w-full">
									<InputLabel value="Title *" />

									<TextInput 
										class="w-full mt-2"
										type="text"
										v-model="form.banner_titles[key]"
									/>

									<InputLabel value="Description *" class="mt-2" />

									<Editor
									:api-key="tinyMCEAPI"
									:init="{
											setup: (event) => finishLoadTinyMce(event, key),
											selector: '#banner_descriptions_'+key,
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
										:id="'banner_descriptions_'+key"
										:name="'banner_descriptions_'+key"
									/>

									<InputLabel value="Description Background Color *" />

									<ColorPicker 
										v-model:pureColor="form.banner_colors[key]"
										v-model:gradientColor="gradientColor"
										format="hex"
									/>
									
									<InputLabel value="Image *" class="mt-2"  />

									<TextInput 
										class="w-full mt-2"
										type="file"
										:id="'file-input-'+key"
										accept=".jpeg,.jpg,.png"
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
											:minContainerWidth="700"
											:minContainerHeight="500"
											:minCropBoxWidth="banner_width"
											:minCropBoxHeight="banner_height"
										/>

										<PrimaryButton v-on:click.prevent="cropBannerImage(key)" class="mt-2">Crop</PrimaryButton>
									</div>

									<div class="mt-2" v-if="banner_sources[key]">
										<img :src="banner_sources[key]" class="w-full">
									</div>

									<div class="mt-2" v-if="!banner_sources[key] && props.id">
										<img :src="'/'+form.banner_images[key]" class="w-72">
									</div>
								</div>
							</div>
						</div>

						<div v-if="form.errors.banner_titles" v-for="(error, errorKey) in form.errors.banner_titles" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.banner_descriptions" v-for="(error, errorKey) in form.errors.banner_descriptions" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.banner_colors" v-for="(error, errorKey) in form.errors.banner_colors" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.banner_images" v-for="(error, errorKey) in form.errors.banner_images" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>
					</fieldset>
				</div>

				<div class="mt-4">
					<fieldset>
						<legend>
							<span class="font-bold">Service:</span>
							<PlusIco class="inline-block ml-2 h-6 stroke-gray-500 hover:cursor-pointer" v-on:click.prevent="addService" />
						</legend>

						<div class="mt-2" v-for="(number, key) in counter_services" :key="key">
							<div class="flex gap-2 w-full">
								<div>
									<div class="font-bold text-center">{{ number+'.' }}</div>
									
									<div class="mt-2" v-if="key > 0">
										<RemoveIco width="25" height="25" class="hover:cursor-pointer" :title="'Delete Service '+number" v-on:click.prevent="deleteService(key)" />
									</div>
								</div>

								<div class="w-full">
									<InputLabel value="Name *" />

									<TextInput 
										class="w-full mt-2"
										type="text"
										v-model="form.service_name[key]"
									/>

									<InputLabel value="Link *" class="mt-2" />

									<TextInput 
										class="w-full mt-2"
										type="text"
										v-model="form.service_links[key]"
									/>

									<InputLabel value="Description *" class="mt-2" />

									<textarea class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-2" v-model="form.service_descriptions[key]"></textarea>
									
									<InputLabel value="Image *" class="mt-2" />

									<TextInput 
										class="w-full mt-2"
										:id="'service_image_'+key"
										type="file"
										accept=".jpeg,.jpg,.png"
										v-on:change="setServiceImage"
										:data-current-key="key"
									/>

									<div class="mt-2" v-if="props.id">
										<img :src="'/'+form.service_images[key]" class="w-10">
									</div>
								</div>
							</div>
						</div>

						<div v-if="form.errors.service_name" v-for="(error, errorKey) in form.errors.service_name" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.service_links" v-for="(error, errorKey) in form.errors.service_links" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.service_descriptions" v-for="(error, errorKey) in form.errors.service_descriptions" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.service_images" v-for="(error, errorKey) in form.errors.service_images" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>
					</fieldset>
				</div>

				<div class="mt-4">
					<fieldset>
						<legend>
							<span class="font-bold">Sector:</span>
							<PlusIco class="inline-block ml-2 h-6 stroke-gray-500 hover:cursor-pointer" v-on:click.prevent="addSector" />
						</legend>

						<div class="mt-2" v-for="(number, key) in counter_sectors" :key="key">
							<div class="flex gap-2 w-full">
								<div>
									<div class="font-bold text-center">{{ number+'.' }}</div>
									
									<div class="mt-2" v-if="key > 0">
										<RemoveIco width="25" height="25" class="hover:cursor-pointer" :title="'Delete Sector '+number" v-on:click.prevent="deleteSector(key)" />
									</div>
								</div>

								<div class="w-full">
									<InputLabel value="Name *" />

									<TextInput 
										class="w-full mt-2"
										type="text"
										v-model="form.sector_name[key]"
									/>

									<InputLabel value="Description *" class="mt-2" />

									<textarea class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-2" v-model="form.sector_descriptions[key]"></textarea>

									<InputLabel value="Image *" class="mt-2" />

									<TextInput 
										class="w-full mt-2"
										:id="'sector_image_'+key"
										type="file"
										accept=".jpeg,.jpg,.png"
										v-on:change="setSectorImage"
										:data-current-key="key"
									/>

									<div class="mt-2" v-if="props.id">
										<img :src="'/'+form.sector_images[key]" class="w-10">
									</div>
								</div>
							</div>
						</div>

						<div v-if="form.errors.sector_name" v-for="(error, errorKey) in form.errors.sector_name" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.sector_descriptions" v-for="(error, errorKey) in form.errors.sector_descriptions" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.sector_images" v-for="(error, errorKey) in form.errors.sector_images" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>
					</fieldset>
				</div>

				<div class="mt-4">
					<fieldset>
						<legend>
							<span class="font-bold">Project:</span>
							<PlusIco class="inline-block ml-2 h-6 stroke-gray-500 hover:cursor-pointer" v-on:click.prevent="addProject" />
						</legend>

						<div class="mt-2" v-for="(number, key) in counter_projects" :key="key">
							<div class="flex gap-2 w-full">
								<div>
									<div class="font-bold text-center">{{ number+'.' }}</div>

									<div class="mt-2" v-if="key > 0">
										<RemoveIco width="25" height="25" class="hover:cursor-pointer" :title="'Delete Project '+number" v-on:click.prevent="deleteProject(key)" />
									</div>
								</div>

								<div class="w-full">
									<InputLabel value="Name *" />

									<TextInput 
										class="w-full mt-2"
										type="text"
										v-model="form.project_name[key]"
									/>

									<InputLabel value="Description *" class="mt-2" />

									<textarea class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-2" v-model="form.project_descriptions[key]"></textarea>

									<InputLabel value="Image *" class="mt-2" />

									<TextInput 
										class="w-full mt-2"
										:id="'project_image_'+key"
										type="file"
										accept=".jpeg,.jpg,.png"
										v-on:change="setProjectImage"
										:data-current-key="key"
									/>

									<div class="mt-2" v-if="props.id">
										<img :src="'/'+form.project_images[key]" class="w-72">
									</div>
								</div>
							</div>
						</div>

						<div v-if="form.errors.project_name" v-for="(error, errorKey) in form.errors.project_name" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.project_descriptions" v-for="(error, errorKey) in form.errors.project_descriptions" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.project_images" v-for="(error, errorKey) in form.errors.project_images" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>
					</fieldset>
				</div>

				<div class="mt-4">
					<fieldset>
						<legend>
							<span class="font-bold">Achievement:</span>
							<PlusIco class="inline-block ml-2 h-6 stroke-gray-500 hover:cursor-pointer" v-on:click.prevent="addAchievement" />
						</legend>

						<div class="mt-2" v-for="(number, key) in counter_achievements" :key="key">
							<div class="flex gap-2 w-full">
								<div>
									<div class="font-bold text-center">{{ number+'.' }}</div>
									
									<div class="mt-2" v-if="key > 0">
										<RemoveIco width="25" height="25" class="hover:cursor-pointer" :title="'Delete Project '+number" v-on:click.prevent="deleteAchievement(key)" />
									</div>
								</div>

								<div class="w-full">
									<InputLabel value="Name *" />

									<TextInput 
										class="w-full mt-2"
										type="text"
										v-model="form.achievement_name[key]"
									/>

									<InputLabel value="Numbers *" class="mt-2" />

									<TextInput 
										class="w-full mt-2"
										type="number"
										v-model="form.achievement_numbers[key]"
									/>

									<InputLabel value="Icon *" class="mt-2" />

									<TextInput 
										class="w-full mt-2"
										:id="'achievement_image_'+key"
										type="file"
										accept=".png"
										v-on:change="setAchievementImage"
										:data-current-key="key"
									/>

									<div class="mt-2" v-if="props.id">
										<img :src="'/'+form.achievement_images[key]" class="w-10">
									</div>
								</div>
							</div>
						</div>

						<div v-if="form.errors.achievement_name" v-for="(error, errorKey) in form.errors.achievement_name" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.achievement_numbers" v-for="(error, errorKey) in form.errors.achievement_numbers" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.achievement_images" v-for="(error, errorKey) in form.errors.achievement_images" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>
					</fieldset>
				</div>

				<div class="mt-4">
					<fieldset>
						<legend>
							<span class="font-bold">Why Choose Us:</span>
							<PlusIco class="inline-block ml-2 h-6 stroke-gray-500 hover:cursor-pointer" v-on:click.prevent="addWhyChooseUs" />
						</legend>

						<div class="mt-2">
							<div>
								<InputLabel value="Other Image *" />

								<TextInput 
									id="why_choose_us_other_images"
									type="file"
									accept=".jpg,.png,.jpeg"
									class="w-full mt-2"
									multiple
									v-on:change="whyChooseUsOtherImage"
								/>
							</div>

							<div class="mt-2 flex gap-4" v-if="props.id">
								<img v-for="(other_image, other_key) in form.why_choose_us_other_images" v-if="typeof other_image != 'object'" :src="'/'+other_image" class="w-10" :key="other_key">
							</div>
						</div>

						<div class="mt-2" v-for="(number, key) in counter_why_choose_us" :key="key">
							<div class="flex gap-2 w-full">
								<div>
									<div class="font-bold text-center">{{ number+'.' }}</div>

									<div class="mt-2" v-if="key > 0">
										<RemoveIco width="25" height="25" class="hover:cursor-pointer" :title="'Delete Why Choose Us '+number" v-on:click.prevent="deleteWhyChooseUs(key)" />
									</div>
								</div>

								<div class="w-full">
									<InputLabel value="Description *" />

									<textarea class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-2" v-model="form.why_choose_us_descriptions[key]"></textarea>

									<InputLabel value="Image *" class="mt-2" />

									<TextInput 
										class="w-full mt-2"
										:id="'why_choose_us_image_'+key"
										type="file"
										accept=".jpeg,.jpg,.png"
										v-on:change="setWhyChooseUsImage"
										:data-current-key="key"
									/>

									<div class="mt-2" v-if="props.id && form.why_choose_us_images[key] && typeof form.why_choose_us_images[key] !== 'object'">
										<img :src="'/'+form.why_choose_us_images[key]" class="w-10">
									</div>
								</div>
							</div>
						</div>

						<div v-if="form.errors.why_choose_us_descriptions" v-for="(error, errorKey) in form.errors.why_choose_us_descriptions" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.why_choose_us_images" v-for="(error, errorKey) in form.errors.why_choose_us_images" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.why_choose_us_other_images" v-for="(error, errorKey) in form.errors.why_choose_us_other_images" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>
					</fieldset>
				</div>

				<div class="mt-4">
					<fieldset>
						<legend>
							<span class="font-bold">Testimonial:</span>
							<PlusIco class="inline-block ml-2 h-6 stroke-gray-500 hover:cursor-pointer" v-on:click.prevent="addTestimonial" />
						</legend>

						<div class="mt-2" v-for="(number, key) in counter_testimonials" :key="key">
							<div class="flex gap-2 w-full">
								<div>
									<div class="font-bold text-center">{{ number+'.' }}</div>
									
									<div class="mt-2" v-if="key > 0">
										<RemoveIco width="25" height="25" class="hover:cursor-pointer" :title="'Delete Project '+number" v-on:click.prevent="deleteTestimonial(key)" />
									</div>
								</div>

								<div class="w-full">
									<InputLabel value="Title *" />

									<TextInput 
										class="w-full mt-2"
										type="text"
										v-model="form.testimonial_titles[key]"
									/>

									<InputLabel value="Image *" class="mt-2" />

									<TextInput 
										class="w-full mt-2"
										:id="'testimonial_image_'+key"
										type="file"
										accept=".jpeg,.jpg,.png"
										v-on:change="setTestimonialImage"
										:data-current-key="key"
									/>

									<InputLabel value="Youtube Link *" class="mt-2" />

									<TextInput 
										class="w-full mt-2"
										type="text"
										v-model="form.testimonial_videos[key]"
									/>

									<div class="mt-2" v-if="props.id">
										<img :src="'/'+form.testimonial_images[key]" class="w-72">
									</div>
								</div>
							</div>
						</div>

						<div v-if="form.errors.testimonial_titles" v-for="(error, errorKey) in form.errors.testimonial_titles" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.testimonial_images" v-for="(error, errorKey) in form.errors.testimonial_images" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>

						<div v-if="form.errors.testimonial_videos" v-for="(error, errorKey) in form.errors.testimonial_videos" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>
					</fieldset>
				</div>

				<div class="mt-4">
					<fieldset>
						<legend>
							<span class="font-bold">Partner:</span>
							<PlusIco class="inline-block ml-2 h-6 stroke-gray-500 hover:cursor-pointer" v-on:click.prevent="addPartner" />
						</legend>

						<div class="mt-2" v-for="(number, key) in counter_partners" :key="key">
							<div class="flex gap-2 w-full">
								<div>
									<div class="font-bold text-center">{{ number+'.' }}</div>
									
									<div class="mt-2" v-if="key > 0">
										<RemoveIco width="25" height="25" class="hover:cursor-pointer" :title="'Delete Project '+number" v-on:click.prevent="deletePartner(key)" />
									</div>
								</div>

								<div class="w-full">
									<InputLabel value="Partner Logo *" />

									<TextInput 
										class="w-full mt-2"
										:id="'partner_image_'+key"
										type="file"
										accept=".jpeg,.jpg,.png"
										v-on:change="setPartnerImage"
										:data-current-key="key"
									/>

									<div class="mt-2" v-if="props.id">
										<img :src="'/'+form.partner_logo[key]">
									</div>
								</div>
							</div>
						</div>

						<div v-if="form.errors.partner_logo" v-for="(error, errorKey) in form.errors.partner_logo" :key="errorKey">
							<InputError :message="error" class="mt-2" />
						</div>
					</fieldset>
				</div>

				<div class="mt-2" v-show="errorResponse">
	            	<InputError :message="errorResponse" class="mt-2 mr-3" />
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

<style>
/*.sm\:max-w-2xl {
	max-width: 65rem;
}*/
</style>