<script setup>
import { ref, onMounted, computed, h } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Check, Ban } from '@vicons/tabler';
import { Head, router } from '@inertiajs/vue3';
import { NSpace, NInput, NCard, NAlert, NSelect, NTag, NCollapse, NCollapseItem, NIcon, NEmpty, NButton, NForm, NFormItem, NDatePicker, NDynamicInput, NColorPicker, NSwitch, useDialog, NTooltip } from 'naive-ui';

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

const project = computed(() => {
    return props.project;
});

const modalMaxWidth = 'md';

const loading = ref(false);

function convertTime(time) {
    time == null ? null : parseInt((new Date(time).getTime() / 1000).toFixed(0)) * 1000
}

// // lifecycle hooks
// onMounted(() => {
//     setTimeout( () => {
//         axios.get('/test')
//         .then(function (response) {
//             response.data.forEach(item => {
//                 // handle success
//                 projectData.value.push(item);
//             });

//             loading.value = false;
//         })
//         .catch(function () {
//             // handle error
//             error.value = true;
//             loading.value = false;
//         })
//     }, 500);
// })

</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="max-w-full">
                            <h1>{{ project.id }}</h1>
                            <h1>{{ project.name }}</h1>
                            <h1>{{ project.description ?? "none" }}</h1>
                            <h1>{{ project.start_date }}</h1>
                            <h1>{{ project.end_date ?? "none" }}</h1>
                            <h1>{{ project.tags }}</h1>
                            <h1>{{ project.languages }}</h1>
                            <h1>{{ project.content }}</h1>
                            <h1>{{ project.created_at }}</h1>
                            <h1>{{ project.updated_at }}</h1>
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
