import ProjectLanguage from "./ProjectLanguage";
import ProjectTag from "./ProjectTag";

interface Project {
    id: number,
    name: string,
    description: string,
    start: Date | 'Not yet started',
    end: Date | 'Current',
    tags: ProjectTag[],
    languages: ProjectLanguage[],
    location: string,
    grade: string | null,
}

export default Project