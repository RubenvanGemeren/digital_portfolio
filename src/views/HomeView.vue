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
					<!-- <button @click="handleClick('Laravel')">order by location</button> -->
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

	console.log(ProjectData);

	let projects2: Project[] = [];

	// Fix this
	for (const project in ProjectData) {
		let projectObject = ProjectData[project as keyof typeof ProjectData];
		try {
			projects2.push();
			let	projectObj: Project = project;
			console.log(projectObj);
			
		} catch (error) {
			console.log(error);
		}
	}
	
		

	const projects = ref<Project[]>([
		{id: 1, name: 'test name', description: 'test description', start: 'Not yet started', end: 'Current', tags: ['fun'], languages: ['PHP'], location: 'Rotterdam', grade: '8' },
		{id: 2, name: 'test name 2', description: 'test description 2', start: 'Not yet started', end: 'Current', tags: ['work'], languages: ['Laravel'], location: 'Rotterdam', grade: '9' },
		{id: 3, name: 'test name 3', description: 'test description 3', start: 'Not yet started', end: 'Current', tags: ['work'], languages: ['Laravel'], location: 'Rotterdam', grade: '9' },
	])

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