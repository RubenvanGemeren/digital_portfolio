<script setup>
import { ref, onMounted, computed, h } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { NSpace, NInput, NCard, NText, NAlert, NDivider ,NThing, NSelect, NTag, NCollapse, NCollapseItem, NIcon, NEmpty, NButton } from 'naive-ui';
import { MoodSad, SquarePlus } from '@vicons/tabler';

// reactive state
const projectData = ref([]);

const error = ref(false);

const loading = ref(true);

const name = ref('');
const projectSearch = ref('');
const description = ref('');
const tags = ref([]);
const langs = ref([]);

// Create custom render for the options
const renderSelectItem = ({ option, handleClose }) => {
      return h(
        NTag,
        {
          type: option.type,
          closable: true,
          onMousedown: (e) => {
            e.preventDefault();
          },
          onClose: (e) => {
            e.stopPropagation();
            handleClose();
          }
        },
        { default: () => option.label }
      );
};

const tagItems = [
        {
          label: "🏫 School",
          value: 'school',
          type: "success",
        },
        {
          label: '🏢 Work',
          value: 'work',
          type: "success",
        },
        {
          label: '🤾‍♂️ Fun',
          value: 'fun',
          type: "success",
        },
        {
          label: "🌐 Web Development",
          value: 'web-development',
          type: "success",
        },
        {
          label: '🚄 Public Transport',
          value: 'public transport',
          type: "success",
        }
]

const langItems = [
        {
          label: "PHP",
          value: 'php',
          type: "info",
        },
        {
          label: 'Javascript',
          value: 'work',
          type: "warning",
        },
        {
          label: 'Laravel',
          value: 'laravel',
          type: "error",
        },
]

// lifecycle hooks
onMounted(() => {
    setTimeout( () => {
        axios.get(route('project.index'))
        .then(function (response) {
            response.data.forEach(item => {
                // handle success
                // console.log(item);
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

// a computed ref
const projects = computed(() => {
    return projectData.value.filter((project) => {
        const projectObject = JSON.parse(JSON.stringify(project));

        if (projectSearch.value.length > 0 ) {
            return filterProjects(projectSearch.value, projectObject.name);
        }

        if (project.tags != null && typeof project.tags != 'object') {
            project.tags = JSON.parse(project.tags);
        }

        if (project.languages != null && typeof project.languages != 'object') {
            project.languages = JSON.parse(project.languages);
        }

        return filterProjects(name.value, projectObject.name) 
            && filterProjects(description.value, projectObject.description)
            && filterProjects(tags.value, projectObject.tags)
            && filterProjects(langs.value, projectObject.languages);
    });
})

function filterProjects(value, object) {

    if (value.length === 0 || (object == null && value.length === 0)) {
        return true;
    } 
    
    if (object != null) {
        if (typeof object == 'object') {
            return object.includes(value)
        } else {
            return object.toLowerCase().includes(value.toLowerCase())
        }
    } else {
        return false;
    }
}

</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>

        <div class="py-3">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                <div class="max-w-lg bg-white overflow-hidden shadow-sm sm:rounded-lg center">
                    <!-- Page content -->
                    <div class="p-6">
                        <n-space vertical justify="space-around">
                            <n-collapse>
                                <n-collapse-item name="1">
                                    <template #header>
                                        <div class="text-green-600 text-base font-bold">
                                            Advanced Filters 
                                        </div>
                                    </template>
                                    <div class="mb-3">
                                        <n-input size="large" v-model:value="name" type="text" round clearable :loading="loading" :disabled="loading" placeholder="Search name" />
                                    </div>
                                    <div class="mb-3">
                                        <n-input size="large" v-model:value="description" round clearable :loading="loading" :disabled="loading" placeholder="Search description" />
                                    </div>
                                    <div class="mt-3">
                                        <n-select size="large" v-model:value="tags" multiple :options="tagItems" :render-tag="renderSelectItem" clearable :loading="loading" :disabled="loading" placeholder="Select tags" />
                                    </div>
                                    <div class="mt-3">
                                        <n-select size="large" v-model:value="langs" multiple :options="langItems" :render-tag="renderSelectItem" clearable :loading="loading" :disabled="loading" placeholder="Select languages" />
                                    </div>
                                </n-collapse-item>
                            </n-collapse>
                        </n-space>
                    </div> 
                </div>
                <div class="bg-white mt-6 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between">
                            <div class="mb-3 max-w-lg">
                                <n-input size="large" v-model:value="projectSearch" type="text" round clearable :loading="loading" :disabled="loading" placeholder="Search projects" />
                            </div>
                            <div>
                                <n-button tag="a" :href="route('project.create')" strong dashed round type="primary" size="small">
                                    Add project
                                    <template #icon>
                                        <n-icon size="20">
                                            <SquarePlus />
                                        </n-icon>
                                    </template>
                                </n-button>
                            </div>
                        </div>
                        <!-- Loading text -->
                        <div class="text-green-500 text-base font-bold text-center" v-if="loading">Loading...</div>

                        <!-- No Info Text -->
                        <div v-if="error">
                            <n-empty size="huge" description="No projects can be found">
                                <template #icon>
                                    <div class="text-red-400">
                                        <n-icon size="55">
                                            <MoodSad />
                                        </n-icon>
                                    </div>
                                </template>
                            </n-empty>
                        </div>
                        <!-- Display projects -->
                        <transition-group name="list" tag="ul">
                            <div class="mb-4" v-if="projects !== null" v-for="project in projects" :key="project.id">
                                <!-- Create loading icon -->
                                <a :href="'projects/' + project.id">
                                    <n-card hoverable>
                                        <template #header>
                                            <div class="text-2xl">
                                                {{ project.name }}
                                            </div>
                                        </template>

                                        <template #header-extra>
                                            <div class="flex">
                                                <div v-if="project.tags !== null" v-for="tags in project.tags">
                                                    <div class="ml-2">
                                                        <n-tag :color="{ color: tags.color, textColor: tags.textColor }" round :bordered="false">
                                                            {{ tags.label }}
                                                        </n-tag>
                                                    </div>
                                                </div>
                                                <div v-if="project.languages !== null" v-for="languages in project.languages">
                                                    <div class="ml-2">
                                                        <n-tag :color="{ color: languages.color, textColor: languages.textColor }" round :bordered="false">
                                                            {{ languages.label }}
                                                        </n-tag>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <div class="text-md">
                                            <div v-if="project.description">
                                                {{ project.description }}
                                            </div>
                                            <div v-else>
                                                <n-text italic depth="3">
                                                    No description given
                                                </n-text>
                                            </div>
                                        </div>

                                        <n-divider />

                                        footer

                                        
                                    </n-card>
                                </a>
                            </div>
                        </transition-group>
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