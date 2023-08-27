<script setup>
import { ref, onMounted, computed, h } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Check, Ban } from '@vicons/tabler';
import { Head, router } from '@inertiajs/vue3';
import { NSpace, NInput, NCard, NAlert, NSelect, NTag, NCollapse, NCollapseItem, NIcon, NEmpty, NButton, NForm, NFormItem, NDatePicker, NDynamicInput, NColorPicker, NSwitch, useDialog, NTooltip } from 'naive-ui';

const props = defineProps({
    record: {
        type: Object,
        required: true,
    },
});

const record = computed(() => {
    return props.record;
});

const disableForm = ref(true);

const active = ref(false);

// Tags
const tags = ref(false);
const confirmTag = ref(false);
const disableTag = ref(false);
const existingTags = ref([]);

// Languages
const langs = ref(false);
const confirmLang = ref(false);
const disableLang = ref(false);
const existingLangs = ref([]);

// Content
const typeOptions = [
        {
            label: "📄 Paragraph",
            value: "paragraph",
        },
        {
            label: "🥧 Pie Chart",
            value: "pie-chart",
            disabled: true,
        },
        {
            label: "📈 Progress",
            value: "progress",
            disabled: true,
        },
]

const modalMaxWidth = 'md';

const loading = ref(false);

const projectData = ref([]);

// Form
const formRef = ref(null);
const formValue = ref({
        name: props.record.name,
        description: props.record.description,
        start_date: parseInt((new Date(props.record.start_date).getTime() / 1000).toFixed(0)) * 1000,
        end_date: parseInt((new Date(props.record.end_date).getTime() / 1000).toFixed(0)) * 1000,
        tags: props.record.tags,
        languages: [],
        content: [],
      });

const railStyle = ({
        focused,
        checked
      }) => {
        const style = {};
        if (checked) {
          style.background = "#5AB14EFF";
          if (focused) {
            style.boxShadow = "0 0 0 2px #20F02040";
          }
        } else {
          style.background = "#2080F0";
          if (focused) {
            style.boxShadow = "0 0 0 2px #2080f040";
          }
        }
        return style;
      }

const rules = {
    record: {
        name: {
            required: true,
            validator(rule, value) {
            if (!value) {
                disableForm.value = true;
                return new Error("Name is required.");
            } else if (value.length < 2) {
                disableForm.value = true;
                return new Error("Name should be at least 2 characters.");
            }
                disableForm.value = false;
                return true;
            },
            trigger: 'blur',
        },
        start_date: {
            required: true,
            validator(rule, value) {
            if (!value) {
                disableForm.value = true;
                return new Error("Start date is required.");
            }
                disableForm.value = false;
                return true;
            },
            trigger: 'blur',
        },
        end_date: {
            required: false,
            validator(rule, value) {
            if (!active.value && !value) {
                disableForm.value = true;
                return new Error("End date is required (Switch to 'active').");
            }
                disableForm.value = false;
                return true;
            },
            trigger: 'blur',
        },
    }
}

const dynamicInputLabelRule = {
        required: true,
        validator(rule, value) {
        if (!value) {
            disableForm.value = true;
            return new Error("Field is required.");
        } else if (value.length < 2) {
            disableForm.value = true;
            return new Error("Field should be at least 2 characters.");
        }
            disableForm.value = false;
            return true;
        },
        trigger: 'blur',
}

const dynamicColorRule = {}

function onCreate() {
    return {
        label: "",
        value: "",
        color: "#000000".replace(/0/g,function(){return (~~(Math.random()*16)).toString(16);}),
        text_color: "#000000".replace(/0/g,function(){return (~~(Math.random()*16)).toString(16);}),
    };
}

function onCreateContent() {
    return {
        type: null,
        header: "",
        subheader: "",
        data: "",
        date: null,
    };
}

function cancelChoice(type, confirmType) {
    loading.value = false;

    switch (type) {
        case 'tags':
            confirmTag.value = false;
            tags.value = false;
            break;
        case 'langs':
            confirmLang.value = false;
            langs.value = false;
            break;
    }
};

function confirmChoice(type) {
    loading.value = false;

    switch (type) {
        case 'tags':
            confirmTag.value = true;
            break;
        case 'langs':
            confirmLang.value = true;
            break;
    }
};

function handleUpdateValue(type) {
    loading.value = true;

    switch (type) {
        case 'tags':
            if (confirmTag.value) {
                cancelChoice(type)
                return;
            }
            tags.value = true;
            break;
        case 'langs':
            if (confirmLang.value) {
                cancelChoice(type)
                return;
            }
            langs.value = true;
            break;
    }
}

function clearEndDate() {
    if (active) {
        formValue.value.end_date = null;
    }
    return;
}

function submit() {
    console.log(formValue.value);
    router.post(route('project.store'), formValue);
}

// lifecycle hooks
onMounted(() => {
    setTimeout( () => {
        axios.get('/test')
        .then(function (response) {
            response.data.forEach(item => {
                // handle success
                projectData.value.push(item);
            });

            loading.value = false;
        })
        .catch(function () {
            // handle error
            error.value = true;
            loading.value = false;
        })
    }, 500);
})

</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <div class="py-3">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="max-w-full">
                            {{ props.record.tags }}
                            <n-form
                                ref="formRef"
                                :model="formValue"
                                :rules="rules"
                                size="large"
                                @submit.prevent="submit"
                            >
                                <n-space vertical>
                                    <n-form-item label="Name" path="name">
                                        <n-input v-model:value="formValue.name" placeholder="Input Name" />
                                    </n-form-item>
                                    <n-form-item label="Description" path="age">
                                        <n-input v-model:value="formValue.description" placeholder="Input description (optional)" />
                                    </n-form-item>
                                    <n-form-item label="Start Date" path="start_date">
                                        <div class="w-full">
                                            <n-date-picker actions="['now', 'clear']" v-model:value="formValue.start_date" format="dd-MM-yyyy" placeholder="Choose date">
                                            </n-date-picker>
                                        </div>
                                    </n-form-item>
                                    <n-form-item label="End Date" path="end_date">
                                        <div class="w-full">
                                            <n-date-picker actions="['now', 'clear']" :disabled="active" v-model:value="formValue.end_date" format="dd-MM-yyyy" placeholder="Choose date" >
                                            </n-date-picker>
                                        </div>
                                        <n-tooltip :show-arrow="false">
                                                <template #trigger>
                                                    <n-switch v-model:value="active" @update:value="clearEndDate()" :rail-style="railStyle" class="ml-2 w-40" size="large">
                                                        <template #checked>
                                                            Is still active
                                                        </template>
                                                        <template #unchecked>
                                                            Has end date
                                                        </template>
                                                    </n-switch>
                                                </template>
                                                    Is this project still active?
                                            </n-tooltip>
                                    </n-form-item>
                                    <div class="flex justify-between">
                                        <div>
                                            Tags
                                        </div>
                                        <div>
                                            <ConfirmModal :show="tags" :maxWidth="modalMaxWidth" @close="cancelChoice('tags')" @confirm="confirmChoice('tags')">
                                                <template #subtitle>
                                                    Selecting this option will remove any <strong>tags</strong> that have been made here.
                                                </template>
                                            </ConfirmModal>
                                        </div>
                                    </div>
                                    <n-select
                                        size="medium"
                                        v-model:value="tags"
                                        multiple
                                        :options="tagItems"
                                        clearable
                                        :loading="loading"
                                        :disabled="loading"
                                        placeholder="Select tags"
                                    />
                                    <n-dynamic-input
                                            class="mb-3"
                                            v-model:value="formValue.tags"
                                            item-style="margin-bottom: 0;"
                                            :on-create="onCreate"
                                            #="{ index, value }"
                                        >
                                        <div class="flex">
                                            <div class="mr-3">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`tags[${index}].label`"
                                                    :rule="dynamicInputLabelRule"
                                                >
                                                    <n-input
                                                        :disabled="disableTag"
                                                        v-model:value="formValue.tags[index].label"
                                                        placeholder="Label"
                                                    />
                                                </n-form-item>
                                            </div>
                                            <div class="mr-3">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`tags[${index}].value`"
                                                    :rule="dynamicInputLabelRule"
                                                >
                                                    <n-input
                                                        :disabled="disableTag"
                                                        v-model:value="formValue.tags[index].value"
                                                        placeholder="Value"
                                                    />
                                                </n-form-item>
                                            </div>
                                            <div class="mr-3 w-40">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`tags[${index}].color`"
                                                    :rule="dynamicInputLabelRule"
                                                >
                                                    <n-color-picker
                                                        v-model:value="formValue.tags[index].color"
                                                        :modes="['hex']"
                                                        :disabled="disableTag"
                                                    />
                                                </n-form-item>
                                            </div>
                                            <div class="mr-3 w-40">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`tags[${index}].text_color`"
                                                    :rule="dynamicInputLabelRule"
                                                >
                                                    <n-color-picker
                                                        :disabled="disableTag"
                                                        v-model:value="formValue.tags[index].text_color"
                                                        :modes="['hex']"
                                                        :swatches="[ '#FFFFFF', '#000000']"
                                                    />
                                                </n-form-item>
                                            </div>
                                            <div class="mt-1">
                                                <n-tag size="medium" round :color="{ color: formValue.tags[index].color, textColor: formValue.tags[index].text_color }">
                                                    {{ formValue.tags[index].label }}
                                                </n-tag>
                                            </div>
                                        </div>
                                    </n-dynamic-input>
                                    <div class="flex justify-between">
                                        <div>
                                            Languages
                                        </div>
                                        <div>
                                            <ConfirmModal :show="langs" :maxWidth="modalMaxWidth" @close="cancelChoice('langs')" @confirm="confirmChoice('langs')">
                                                <template #subtitle>
                                                    Selecting this option will remove any <strong>languages</strong> that have been made here.
                                                </template>
                                            </ConfirmModal>
                                            <!-- <n-switch
                                                size="large"
                                                :rubber-band="false"
                                                :value="confirmLang"
                                                :loading="loading"
                                                @update:value="handleUpdateValue('langs')"
                                            /> -->
                                        </div>
                                    </div>
                                    <n-select
                                        size="medium"
                                        v-model:value="tags"
                                        multiple
                                        :options="tagItems"
                                        clearable
                                        :loading="loading"
                                        :disabled="loading"
                                        placeholder="Select languages"
                                    />
                                    <n-dynamic-input
                                            class="mb-3"
                                            v-model:value="formValue.languages"
                                            item-style="margin-bottom: 0;"
                                            :on-create="onCreate"
                                            #="{ index, value }"
                                        >
                                        <div class="flex">
                                            <div class="mr-3">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`languages[${index}].label`"
                                                    :rule="dynamicInputLabelRule"
                                                >
                                                    <n-input
                                                        v-model:value="formValue.languages[index].label"
                                                        placeholder="Label"
                                                    />
                                                </n-form-item>
                                            </div>
                                            <div class="mr-3">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`languages[${index}].value`"
                                                    :rule="dynamicInputLabelRule"
                                                >
                                                    <n-input
                                                        v-model:value="formValue.languages[index].value"
                                                        placeholder="Value"
                                                    />
                                                </n-form-item>
                                            </div>
                                            <div class="mr-3 w-40">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`languages[${index}].color`"
                                                >
                                                    <n-color-picker v-model:value="formValue.languages[index].color" :modes="['hex']"  />
                                                </n-form-item>
                                            </div>
                                            <div class="mr-3 w-40">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`languages[${index}].text_color`"
                                                >
                                                    <n-color-picker v-model:value="formValue.languages[index].text_color" :modes="['hex']" :swatches="[ '#FFFFFF', '#000000']"  />
                                                </n-form-item>
                                            </div>
                                            <div class="mt-1">
                                                <n-tag size="medium" round :color="{ color: formValue.languages[index].color, textColor: formValue.languages[index].text_color }">
                                                    {{ formValue.languages[index].label }}
                                                </n-tag>
                                            </div>
                                        </div>
                                    </n-dynamic-input>

                                    <div class="flex justify-between">
                                        <div>
                                            Content
                                        </div>
                                    </div>
                                    <n-dynamic-input
                                            class="mb-3"
                                            v-model:value="formValue.content"
                                            item-style="margin-bottom: 0;"
                                            :on-create="onCreateContent"
                                            #="{ index, value }"
                                        >
                                        <div class="flex flex-col p-3 mb-3 rounded-lg border-dashed border border-green-500 hover:border-green-400">
                                            <div class="flex">
                                                <div class="mr-3 w-40">
                                                    <n-form-item
                                                        ignore-path-change
                                                        :show-label="false"
                                                        :path="`content[${index}].type`"
                                                        :rule="dynamicInputLabelRule"
                                                    >
                                                        <n-select
                                                            v-model:value="formValue.content[index].type" :options="typeOptions"
                                                            size="medium"
                                                            placeholder="Select type"
                                                        />
                                                    </n-form-item>
                                                </div>
                                                <div class="mr-3">
                                                    <n-form-item
                                                        ignore-path-change
                                                        :show-label="false"
                                                        :path="`content[${index}].header`"
                                                    >
                                                        <n-input
                                                            v-model:value="formValue.content[index].header"
                                                            placeholder="Header (optional)"
                                                            size="medium"
                                                        />
                                                    </n-form-item>
                                                </div>
                                                <div class="mr-3 w-60">
                                                    <n-form-item
                                                        ignore-path-change
                                                        :show-label="false"
                                                        :path="`content[${index}].subheader`"
                                                    >
                                                        <n-input
                                                            v-model:value="formValue.content[index].subheader"
                                                            placeholder="Sub header (optional)"
                                                            size="medium"
                                                        />
                                                    </n-form-item>
                                                </div>
                                                <div class="mr-3 w-40">
                                                    <n-form-item
                                                        ignore-path-change
                                                        :show-label="false"
                                                        :path="`content[${index}].date`"
                                                        :rule="dynamicInputLabelRule"
                                                    >
                                                        <n-date-picker
                                                            v-model:value="formValue.content[index].date" format="dd-MM-yyyy" placeholder="Choose date"
                                                        />
                                                    </n-form-item>
                                                </div>
                                            </div>
                                            <div class="mr-3">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`content[${index}].data`"
                                                >
                                                    <n-input
                                                        v-model:value="formValue.content[index].data"
                                                        placeholder="Type text (optional)"
                                                        type="textarea"
                                                    />
                                                </n-form-item>
                                            </div>
                                        </div>
                                    </n-dynamic-input>


                                    <div class="flex justify-between">
                                        <div>
                                            <n-tooltip :disabled="!disableForm" placement="left">
                                                <template #trigger>
                                                    <n-button

                                                        @click=""
                                                        ghost
                                                        strong
                                                        round
                                                        type="primary"
                                                        attr-type="submit"
                                                        size="small"
                                                    >
                                                            Save
                                                        <template #icon>
                                                            <n-icon size="20">
                                                                <Check />
                                                            </n-icon>
                                                        </template>
                                                    </n-button>
                                                </template>
                                                    Make sure all fields have been filled in correctly.
                                            </n-tooltip>
                                        </div>
                                        <div>
                                            <n-button tag="a" :href="route('home')" ghost strong round type="warning" size="small">
                                                Cancel
                                                <template #icon>
                                                    <n-icon size="20">
                                                        <Ban />
                                                    </n-icon>
                                                </template>
                                            </n-button>
                                        </div>
                                    </div>
                                </n-space>
                            </n-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>

.list-move, /* apply transition to moving elements */
.list-enter-active,
.list-leave-active {
    transition: all 0.8s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
animations can be calculated correctly. */
.list-leave-active {
    position: absolute;
}

</style>
