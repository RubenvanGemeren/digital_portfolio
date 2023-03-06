<template>
    <div class="projectsShow">
      <h1>This is the projects page for project: {{ $route.params.id }}</h1>
      <h1>Project Details</h1>
      <ul>
        <li> {{ projectItem?.id }} </li>
        <li> {{ projectItem?.name }} </li>
        <li> {{ projectItem?.description }} </li>
        <li> {{ projectItem?.start }} </li>
        <li> {{ projectItem?.end }} </li>
        <li> {{ projectItem?.tags }} </li>
        <li> {{ projectItem?.languages }} </li>
        <li> {{ projectItem?.location }} </li>
        <li> {{ projectItem?.grade }} </li>
      </ul>
    </div>
</template>

<script lang="ts">

import { defineComponent } from "vue";
import Project from "@/types/Project";
import ProjectTag from "@/types/ProjectTag";
import ProjectLanguage from "@/types/ProjectLanguage";
import ProjectData from '@/data/projects.json';
import { useRoute } from "vue-router";

export default defineComponent ({
	name: 'ProjectShowView',
	setup() {
    
  const route = useRoute()
  const projectId = route.params.id;

  let projectItem;

		try {
				const project = ProjectData["Project " + projectId as keyof typeof ProjectData];
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
        projectItem = projectObject;
		} catch (error) {
			console.error(error);
		}	
  return { projectItem }
		
	}
})

</script>

