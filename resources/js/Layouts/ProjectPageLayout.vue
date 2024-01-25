
<template>
    <div class="project-layout">
        <h1 class="header">Name</h1>
        <h2 class="sub-header">Project ID: 1</h2>
        <div class="description">Description</div>
        <div class="dates">
            <div class="start-date">Start Date: 00:00:00:00</div>
            <div class="end-date">End Date: 00:00:00:00</div>
        </div>
        <div class="tags">
            <h1>Tags</h1>
        </div>
        <div class="content">
            <h1>TEST</h1>
            <!-- <div class="content-piece" v-for="piece in project.content" :key="piece.id">
                {{ piece.text }}
            </div> -->
        </div>
        <div class="timestamps">
            <div class="created-at">Created At: 00:00:00:00</div>
            <div class="updated-at">Updated At: 00:00:00:00</div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ProjectPageLayout',
    props: {
        project: {
            type: Object,
            required: true
        }
    }
}
</script>

<style scoped>
.header {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

.sub-header {
    font-size: 18px;
    margin-bottom: 10px;
}

.description {
    margin-bottom: 20px;
}

.dates {
    margin-bottom: 20px;
}

.tags {
    margin-bottom: 20px;
}

.tag {
    display: inline-block;
    background-color: #f0f0f0;
    padding: 5px 10px;
    margin-right: 5px;
    border-radius: 5px;
}

.content {
    margin-bottom: 20px;
}

.content-piece {
    margin-bottom: 10px;
}

.timestamps {
    font-size: 12px;
    color: #888888;
}
</style>
