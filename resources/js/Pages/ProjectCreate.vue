<script setup>
import { ref, onMounted, computed, h } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Check, Ban } from '@vicons/tabler';
import { Head } from '@inertiajs/vue3';
import { NSpace, NInput, NCard, NAlert, NSelect, NTag, NCollapse, NCollapseItem, NIcon, NEmpty, NButton, NForm, NFormItem, NDatePicker, NDynamicInput, NColorPicker, NSwitch, useDialog } from 'naive-ui';

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

const modalMaxWidth = 'md';

const loading = ref(false);

const projectData = ref([]);

// Form
const formRef = ref(null);
const formValue = ref({
        record: {
            name: "",
            description: "",
            start_date: Date.now(),
            end_date: Date.now(),
        },
        tags: [],
        languages: [],
        content: {}
      });

function onCreate() {
    return {
        label: "",
        value: "",
        color: "#000000".replace(/0/g,function(){return (~~(Math.random()*16)).toString(16);}),
        text_color: "#000000".replace(/0/g,function(){return (~~(Math.random()*16)).toString(16);}),
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
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="max-w-full">
                            <n-form
                                ref="formRef"
                                :model="formValue"
                                size="large"
                            >
                                <n-space vertical>
                                    <n-form-item label="Name" path="record.name">
                                        <n-input v-model:value="formValue.record.name" placeholder="Input Name" />
                                    </n-form-item>
                                    <n-form-item label="Description" path="record.age">
                                        <n-input v-model:value="formValue.record.description" placeholder="Input Age" />
                                    </n-form-item>
                                    <n-form-item label="Start Date" path="record.start_date">
                                        <div class="w-full">
                                            <n-date-picker v-model:value="formValue.record.start_date" format="dd-MM-yyyy" placeholder="Choose date" />
                                        </div>
                                    </n-form-item>
                                    <n-form-item label="End Date" path="record.end_date">
                                        <div class="w-full">
                                            <n-date-picker v-model:value="formValue.record.end_date" format="dd-MM-yyyy" placeholder="Choose date" />
                                        </div>
                                    </n-form-item>
                                    <div class="flex justify-between mb-3">
                                        <div>
                                            Tags
                                        </div>
                                        <div>
                                            <ConfirmModal :show="tags" :maxWidth="modalMaxWidth" @close="cancelChoice('tags')" @confirm="confirmChoice('tags')">
                                                <template #subtitle>
                                                    Selecting this option will remove any <strong>tags</strong> that have been made here.
                                                </template>
                                            </ConfirmModal>
                                            <n-switch 
                                                size="large" 
                                                :rubber-band="false"
                                                :value="confirmTag"
                                                :loading="loading"
                                                @update:value="handleUpdateValue('tags')"
                                            />
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
                                                >
                                                    <n-input
                                                        :disabled="disableTag"
                                                        v-model:value="formValue.tags[index].value"
                                                        placeholder="Value (optional)"
                                                    />
                                                </n-form-item>
                                            </div>
                                            <div class="mr-3 w-40">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`tags[${index}].color`"
                                                >
                                                    <n-color-picker 
                                                        v-model:value="formValue.tags[index].color"
                                                        :modes="['hex']"
                                                        :show-alpha="false" 
                                                        :disabled="disableTag"
                                                    />
                                                </n-form-item>
                                            </div>
                                            <div class="mr-3 w-40">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`tags[${index}].text_color`"
                                                >
                                                    <n-color-picker 
                                                        :disabled="disableTag"
                                                        v-model:value="formValue.tags[index].text_color" 
                                                        :modes="['hex']" 
                                                        :swatches="[ '#FFFFFF', '#000000']" 
                                                        :show-alpha="false" />
                                                </n-form-item>
                                            </div>
                                            <div class="mt-1">
                                                <n-tag size="medium" round :color="{ color: formValue.tags[index].color, textColor: formValue.tags[index].text_color }">
                                                    {{ formValue.tags[index].label }}
                                                </n-tag>
                                            </div>
                                        </div>
                                    </n-dynamic-input>
                                    <div class="flex justify-between mb-3">
                                        <div>
                                            Languages
                                        </div>
                                        <div>
                                            <ConfirmModal :show="langs" :maxWidth="modalMaxWidth" @close="cancelChoice('langs')" @confirm="confirmChoice('langs')">
                                                <template #subtitle>
                                                    Selecting this option will remove any <strong>languages</strong> that have been made here.
                                                </template>
                                            </ConfirmModal>
                                            <n-switch 
                                                size="large" 
                                                :rubber-band="false"
                                                :value="confirmLang"
                                                :loading="loading"
                                                @update:value="handleUpdateValue('langs')"
                                            />
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
                                                >
                                                    <n-input
                                                        v-model:value="formValue.languages[index].value"
                                                        placeholder="Value (optional)"
                                                    />
                                                </n-form-item>
                                            </div>
                                            <div class="mr-3 w-40">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`languages[${index}].color`"
                                                >
                                                    <n-color-picker v-model:value="formValue.languages[index].color" :modes="['hex']" :show-alpha="false" />
                                                </n-form-item>
                                            </div>
                                            <div class="mr-3 w-40">
                                                <n-form-item
                                                    ignore-path-change
                                                    :show-label="false"
                                                    :path="`languages[${index}].text_color`"
                                                >
                                                    <n-color-picker v-model:value="formValue.languages[index].text_color" :modes="['hex']" :swatches="[ '#FFFFFF', '#000000']" :show-alpha="false" />
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
                                            <n-button @click="" ghost strong round type="primary" size="small">
                                                Save
                                                <template #icon>
                                                    <n-icon size="20">
                                                        <Check />
                                                    </n-icon>
                                                </template>
                                            </n-button>
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