<script setup>
import { ref, reactive } from 'vue';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { validationErrors } from '@/Library/helpers.js';

const emit = defineEmits(["success"]);

const props = defineProps({
	title: String,
	id: {
		type: String,
		default: null,
	}
});

const form = reactive({
	to_email: '',
	whatsapp_number: '',
	errors: [],
});

const form_is_open = ref(false), processing = ref(false), tinyMCEAPI = ref(import.meta.env.VITE_TINYMCE_API_KEY), errorResponse = ref('');

const openForm = () => {
	form_is_open.value = true;

	if(props.id) {
		axios.get(route('contact_us.edit', { contactUs: props.id })).then((response) => {
			const { to_email, whatsapp_number } = response.data.results;

			form.to_email = to_email;
			form.whatsapp_number = whatsapp_number ? whatsapp_number.substring(2, String(whatsapp_number).length) : '';
		}).catch((error) => {
			alert("Something when wrong. Please try again");
		});
	}
};

const closeForm = () => {
	form_is_open.value = false;

	form.to_email = '';
	form.whatsapp_number = '';
	form.errors = [];
	processing.value = false;
	errorResponse.value = '';
};

const submitForm = () => {
	processing.value = true;

	let uri = route("contact_us.store");

	if(props.id) {
		form["_method"] = "PUT";

		uri = route("contact_us.update", { contactUs: props.id });
	}

	axios.post(uri, form).then((response) => {
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
</script>

<template>
	<span>
		<span v-on:click.prevent="openForm">
			<slot />
		</span>

		<DialogModal :show="form_is_open" @close="closeForm" :closeable="false">
			<template #title>
				{{ title }}
			</template>

			<template #content>
				<div>
					<div>
						<InputLabel value="To Email *" />

						<TextInput 
							type="email"
							class="w-full mt-2"
							v-model="form.to_email"
						/>

						<div v-for="(error, key) in form.errors.to_email" :key="key">
							<InputError :message="error" class="mt-2" />
						</div>
					</div>

					<div class="mt-4">
						<InputLabel value="Whatsapp Number" />

						<div class="relative">
							<div class="mt-2">
								<TextInput 
									type="number"
									class="w-full pl-9"
									v-model="form.whatsapp_number"
								/>
							</div>
							<div class="absolute top-1/2 left-2" style="transform: translateY(-50%);">
								<span class="text-base">+62</span>
							</div>
						</div>

						<div v-for="(error, key) in form.errors.whatsapp_number" :key="key">
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