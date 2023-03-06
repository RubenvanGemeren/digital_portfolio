<template>
		<div class="home">
			<header>
				<div>  
					<h1 class="title-name">Hi, I'm Ruben van Gemeren</h1>
					<h2>And i'm a software developer. For the past 5 years I have grown from complete beginner to working with many companies realizing innovative solutions on problems ranging from security to optimalization</h2>
				</div>
			<div class="order">
				<button @click="handleClick('clear')">Clear filter</button>
				<button @click="handleClick('work')">Select work</button>
				<button @click="handleClick('PHP')">Select PHP</button>
			</div>
			</header>
		<ProjectList :projects="projects" :order="order"/>
	</div>
</template>

<script lang="ts">

import { defineComponent, ref } from 'vue';
import Project from '@/types/Project';
import ProjectLanguage from '@/types/ProjectLanguage';
import ProjectTag from '@/types/ProjectTag';
import ProjectList from '@/components/ProjectList.vue';
import ProjectOrderTerm from '@/types/ProjectOrderTerm';
import ProjectData from '@/data/projects.json';

export default defineComponent ({
	name: 'HomeView',
	components: { ProjectList },
	setup() {

	const projects: Project[] = [];

		try {
			for (const key in ProjectData) {
				const project = ProjectData[key as keyof typeof ProjectData];
				const projectObject: Project = {
					id: project.id,
					name: project.name,
					description: project.description,
					start: new Date(project.start),
					end: new Date(project.end),
					tags: project.tags.map((tag: string) => tag as ProjectTag),
					languages: project.languages.map((language: string) => language as ProjectLanguage),
					location: project.location,
					grade: project.grade,
				};
					projects.push(projectObject);
			}
		} catch (error) {
			console.error(error);
		}	

	const order = ref<ProjectOrderTerm>('clear')

	// On click
	const handleClick = (term: ProjectOrderTerm) => {
		order.value = term;
	}

	return { projects, handleClick, order}
		
	}
})

</script>


<style>

.title-name {
	font-size: 3em;
}

</style>