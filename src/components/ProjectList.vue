<template>
    <div class="project-list">
        <p>Order by {{ order }}</p>
        <transition-group name="list" tag="ul">
            <li v-for="project in orderedProjects" :key="project.id">
              <router-link
                :to="`/projects/${project.id}`"
                custom
                v-slot="{ navigate }"
              >
                <h2 @click="navigate">Project named: {{ project.name }}</h2>
                <div class="tags">
                    <img src="../assets/rupee.svg" alt="rupee icon">
                    <p>tags: {{ project.tags }}</p>
                    <p>languages: {{ project.languages }}</p>
                </div>
                <div class="description"><p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Fugit totam est inventore sit animi architecto enim corporis molestiae eveniet, tempora voluptas doloribus aperiam, corrupti dolores quos voluptatem sunt ab tempore.</p></div>
              </router-link>
            </li>
        </transition-group>
    </div>
</template>


<script lang="ts">

import { computed, defineComponent, PropType, TransitionGroup } from "vue";
import Project from "@/types/Project";
import ProjectOrderTerm from "@/types/ProjectOrderTerm";
import ProjectLanguage from "@/types/ProjectLanguage";
import ProjectTag from "@/types/ProjectTag";

export default defineComponent({
    props: {
        projects : { 
            required: true,
            type: Array as PropType<Project[]>
        },
        order : {
            required: true,
            type: String as PropType<ProjectOrderTerm>
        },
    },
    setup(props) {
        const orderedProjects = computed(() => {
            return [...props.projects].filter((project: Project) => { 
              console.log(props.order);

              if ( props.order != 'clear' ) {
                // Abusing 'as'
                let orderTypeLanguage = props.order as ProjectLanguage;
                let orderTypeTag = props.order as ProjectTag;

                if (project.languages.includes(orderTypeLanguage)){
                  console.log("select based on lan");
                  return true
                }
                if (project.tags.includes(orderTypeTag)){
                  console.log("select based on tag");
                  return true
                }
                return false
              }
            
              return true;
            })
        })
        return { orderedProjects }
    }
})

</script>


<style>
.project-list {
    max-width: 960px;
    margin: 40px auto;
  }
  .project-list ul {
    padding: 0;
  }
  .project-list li {
    list-style-type: none;
    background: white;
    padding: 16px;
    margin: 16px 0;
    border-radius: 4px;
  }
  .project-list h2 {
    margin: 0 0 10px;
    text-transform: capitalize;
  }
  .tags {
    display: flex;
  }
  .tags img {
    width: 30px;
  }
  .tags p {
    color: #17bf66;
    font-weight: bold;
    margin: 10px 4px;
  }
  
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
