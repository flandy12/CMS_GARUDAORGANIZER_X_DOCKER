<script setup>
import { ref, reactive } from "vue";
import DialogModal from "@/Components/DialogModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Editor from "@tinymce/tinymce-vue";
import VueCropper from "@ballcat/vue-cropper";
import "cropperjs/dist/cropper.css";
import PlusIco from "@/Components/Icons/Plus.vue";
import RemoveIco from "@/Components/Icons/Close.vue";
import {
    validationErrors,
    getFormData,
    toDataURL,
    dataURLtoFile,
} from "@/Library/helpers.js";

const emit = defineEmits(["success"]);

const props = defineProps({
    title: String,
    id: {
        type: String,
        default: null,
    },
});

const form = reactive({
    banner: "",
    wording: "",
    project_titles: [],
    project_descriptions: [],
    project_images: [],
    errors: [],
});

const form_is_open = ref(false),
    processing = ref(false),
    tinyMCEAPI = ref(import.meta.env.VITE_TINYMCE_API_KEY),
    banner_cropper = ref(null),
    temp_banner = ref(null),
    banner_width = ref(1200),
    banner_height = ref(400),
    banner_source = ref(null),
    counter_projects = ref(0),
    errorResponse = ref(""),
    temps = ref([]);

const openForm = () => {
    form_is_open.value = true;

    if (props.id) {
        axios
            .get(route("project.edit", { project: props.id }))
            .then((response) => {
                const { banner, wording, results } = response.data.results;

                form.banner = banner;
                form.wording = wording;

                counter_projects.value = results.length;

                for (let i = 0; i < results.length; i++) {
                    const ext = results[i].image.split(".");
                    const uri = results[i].image;

                    toDataURL("https://cms.garudaorganizer.com/" + uri).then(
                        (base64) => {
                            form.project_images.push(
                                dataURLtoFile(
                                    base64,
                                    "filename_" + i + "." + ext[1]
                                )
                            );
                        }
                    );

                    temps.value.push(results[i].image);
                }

                pushFormValue(
                    results,
                    ["title", "description"],
                    ["project_titles", "project_descriptions"]
                );

                console.log(form.project_images);
            })
            .catch((error) => {
                alert("Something when wrong. Please try again");
            });
    }
};

const pushFormValue = (results, fields, form_fields) => {
    if (results) {
        results.map((result, key) => {
            for (let i = 0; i < form_fields.length; i++) {
                form[form_fields[i]].push(result[fields[i]]);
            }
        });
    }
};

const closeForm = () => {
    form_is_open.value = false;

    form.banner = "";
    form.wording = "";
    form.project_titles = [];
    form.project_descriptions = [];
    form.project_images = [];
    form.errors = [];
    counter_projects.value = 1;
    banner_source.value = null;
    banner_source.value = null;
    banner_cropper.value = null;
    processing.value = false;
    errorResponse.value = "";
};

const submitForm = () => {
    processing.value = true;

    form.errors = [];

    errorResponse.value = "";

    form.wording = tinymce.get("wording").getContent();

    let uri = route("project.store");

    const formData = getFormData(form);

    if (props.id) {
        formData.append("_method", "PUT");

        uri = route("project.update", { project: props.id });
    }

    axios
        .post(uri, formData)
        .then((response) => {
            processing.value = false;

            emit("success", response.data.title, response.data.message);

            closeForm();
        })
        .catch((error) => {
            processing.value = false;

            const { response } = error;
            const { status, data, statusText } = response;
            const { errors, message } = data;

            if (status === 422) {
                form.errors = validationErrors(errors);
            } else {
                errorResponse.value = message;
            }
        });
};

const finishLoadTinyMce = (event) => {
    event.on("init", (args) => {
        tinymce.get("wording").setContent(form.wording);
    });
};

const selectBanner = (event) => {
    const { files } = event.currentTarget;

    if (files.length > 0) {
        temp_banner.value = URL.createObjectURL(files[0]);

        setTimeout(() => {
            document.getElementById("file-input").value = "";

            banner_cropper.value.setCropBoxData({
                height: banner_height.value,
                width: banner_width.value,
            });
        }, 500);
    }
};

const cropBannerImage = () => {
    banner_cropper.value
        .getCroppedCanvas({
            width: banner_width.value,
            height: banner_height.value,
        })
        .toBlob(
            (blob) => {
                const myFile = new File([blob], "image.png", {
                    type: blob.type,
                });

                form.banner = myFile;
                temp_banner.value = null;
                banner_source.value = URL.createObjectURL(blob);
            },
            "image/png",
            0.55
        );
};

const addProject = (event) => {
    counter_projects.value += 1;
};

const deleteProject = (event) => {
    const key = parseInt(event.currentTarget.getAttribute("data-key"));

    counter_projects.value -= 1;

    form.project_titles.splice(key, 1);

    form.project_descriptions.splice(key, 1);

    form.project_images.splice(key, 1);

    temps.value.splice(key, 1);
};

// const setProjectImage = (event) => {
//     const current_key = parseInt(
//             event.currentTarget.getAttribute("data-current-key")
//         ),
//         file = event.currentTarget.files[0];

//     if (form.project_images[current_key] != "undefined") {
//         form.project_images[current_key] = file;
//     } else {
//         form.project_images.push(file);
//     }
//     // const currentKey = parseInt(
//     //     event.currentTarget.getAttribute("data-current-key")
//     // );
//     // const file = event.target.files[0];

//     // if (file) {
//     //     // Replace the existing file at the current index or add a new one
//     //     form.project_images[currentKey] = file;
//     // }
// };

const setProjectImage = (event) => {
    const currentKey = parseInt(
        event.currentTarget.getAttribute("data-current-key")
    );
    const file = event.target.files[0];

    if (file) {
        if (currentKey < form.project_images.length) {
            form.project_images[currentKey] = file;
        } else {
            form.project_images.push(file);
        }
    }
};
</script>

<template>
    <span>
        <span v-on:click.prevent="openForm">
            <slot />
        </span>

        <DialogModal
            :show="form_is_open"
            @close="closeForm"
            :closeable="false"
            maxWidth="64rem"
        >
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

                            <PrimaryButton
                                v-on:click.prevent="cropBannerImage(key)"
                                class="mt-2"
                                >Crop</PrimaryButton
                            >
                        </div>

                        <div class="mt-2" v-if="banner_source">
                            <img :src="banner_source" class="w-72" />
                        </div>

                        <div class="mt-2" v-if="!banner_source && props.id">
                            <img :src="form.banner" class="w-72" />
                        </div>

                        <div
                            v-for="(error, key) in form.errors.banner"
                            :key="key"
                        >
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
                                toolbar:
                                    'undo redo | blocks fontsize | bold italic underline strikethrough | forecolor backcolor | numlist bullist indent outdent | align lineheight',
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
                                remove_script_host: false,
                            }"
                            :id="'wording'"
                            :name="'wording'"
                        />

                        <div
                            v-for="(error, key) in form.errors.wording"
                            :key="key"
                        >
                            <InputError :message="error" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <fieldset>
                            <legend>
                                <span class="font-bold">Title:</span>
                                <PlusIco
                                    class="inline-block ml-2 h-6 stroke-gray-500 hover:cursor-pointer"
                                    v-on:click.prevent="addProject"
                                />
                            </legend>

                            <div
                                class="mt-2"
                                v-for="(number, key) in counter_projects"
                                :key="key"
                            >
                                <div class="flex gap-2 w-full">
                                    <div>
                                        <div class="font-bold text-center">
                                            {{ number + "." }}
                                        </div>
                                        <div class="mt-2">
                                            <RemoveIco
                                                width="25"
                                                height="25"
                                                class="hover:cursor-pointer"
                                                :title="
                                                    'Delete Project ' + number
                                                "
                                                v-on:click.prevent="
                                                    deleteProject
                                                "
                                                :data-key="key"
                                            />
                                        </div>
                                    </div>

                                    <div class="w-full">
                                        <InputLabel value="Title *" />

                                        <TextInput
                                            class="w-full mt-2"
                                            type="text"
                                            v-model="form.project_titles[key]"
                                        />

                                        <InputLabel
                                            value="Description *"
                                            class="mt-2"
                                        />

                                        <textarea
                                            v-model="
                                                form.project_descriptions[key]
                                            "
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-2"
                                        ></textarea>

                                        <InputLabel
                                            value="Image *"
                                            class="mt-2"
                                        />

                                        <TextInput
                                            type="file"
                                            accept=".png,.jpeg,.jpg"
                                            class="w-full mt-2"
                                            :id="'project_image_' + key"
                                            v-on:change="setProjectImage"
                                            :data-current-key="key"
                                        />

                                        <div class="mt-2" v-if="props.id">
                                            <img
                                                :src="'/' + temps[key]"
                                                class="w-10"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                v-if="form.errors.project_titles"
                                v-for="(error, errorKey) in form.errors
                                    .project_titles"
                                :key="errorKey"
                            >
                                <InputError :message="error" class="mt-2" />
                            </div>

                            <div
                                v-if="form.errors.project_descriptions"
                                v-for="(error, errorKey) in form.errors
                                    .project_descriptions"
                                :key="errorKey"
                            >
                                <InputError :message="error" class="mt-2" />
                            </div>

                            <div
                                v-if="form.errors.project_images"
                                v-for="(error, errorKey) in form.errors
                                    .project_images"
                                :key="errorKey"
                            >
                                <InputError :message="error" class="mt-2" />
                            </div>
                        </fieldset>
                    </div>

                    <div class="mt-2" v-show="errorResponse">
                        <InputError
                            :message="errorResponse"
                            class="mt-2 mr-3"
                        />
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
