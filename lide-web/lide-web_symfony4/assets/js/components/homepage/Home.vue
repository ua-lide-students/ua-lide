<template>
    <v-app class="h-screen" style="overflow:hidden">
        <v-toolbar app color="primary" dark>
            <img
                    src="http://uatalents.univ-angers.fr/sites/default/files/inline-images/ua_h_couleur.png"
                    style="text-align: left;height :50%;">
            <v-toolbar-title>
                <v-btn flat :href="userProfilePage" data-cypress="profile-link">{{currentUser.username}}</v-btn>
            </v-toolbar-title>
            <v-spacer />
            <v-btn 
                v-if="isUserAdmin"
                flat
                :href="adminUrl"
                data-cypress="admin-link"
            >
                Administration
            </v-btn>
            <v-btn 
                flat
                :href="logoutLink"
                data-cypress="logout-link"
            >
                Log out
            </v-btn>
        </v-toolbar>
        <v-content app style="background-color: #eeeeee;">
            <v-container fill-height> <!--Border juste pour visualiasition block -->
                <v-layout column reverse>
                    <!-- Buttons to create new projects -->
                    <v-flex shrink>
                        <v-layout column align-center justify-center>
                            <v-btn @click="openNewProjectDialog" class="w-full shadow-lg primary" data-cypress="new-project">Nouveau Projet
                            </v-btn>

                            <!-- Not implemented yet -->
                            <!-- <v-btn @click="openNewProjectFromModelDialog" class="w-full shadow-lg primary" data-cypress="new-project-from-model">Nouveau Projet à partir d'un
                                modèle
                            </v-btn> -->
                        </v-layout>
                    </v-flex>
                    <!-- View when loading projects -->
                    <v-flex class="h-full"> 
                        <div v-if="!projectsLoaded" class="w-full flex align-center justify-center max-h-full" data-cypress="loader">
                            <lide-loading-spinner :size="120" :color="$vuetify.theme.primary"></lide-loading-spinner>
                            <!-- Spinner chargement projet -->
                        </div>
                        <!-- View if user has no projects -->
                        <div class="divNouveauProjet" style="text-align:center;margin:auto;"
                             v-else-if="projects.length === 0" data-cypress="project-list-empty">
                            <h1>Vous n'avez aucun projet</h1>
                            <p>Première visite ? Vous pouvez consulter notre <a>manuel d'utilisation</a></p>
                        </div>
                        <!-- If user has projects, list them -->
                        <div v-else class="w-full relative h-full" data-cypress="project-list">
                            <div class="absolute w-full h-full" style="overflow: auto;">
                                <div class=" layout align-center row border mb-2 p-2 border-blue w-full rounded white shadow-lg" 
                                    v-for="(projet, index) in projects" 
                                    :key="index"
                                    data-cypress="project-list-item">
                                    <div><a :href="urlIdeProjet(projet)" class="text-black" data-cypress="project-link">{{ projet.name }}</a></div>
                                    <v-spacer/>
                                    <v-btn small :href="urlIdeProjet(projet)" color="success"><v-icon>fas fa-edit</v-icon></v-btn>
                                    <v-btn small color="error" @click="deleteProject(index)"><v-icon>fas fa-trash-alt</v-icon></v-btn>
                                </div>
                            </div>
                        </div>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>

        <!-- Dialog new project form -->
        <v-dialog v-model="showNewProjectDialog" width="500">
            <v-card data-cypress="new-project-form">
                <v-card-title primary-title>
                    <div>
                        <h1 class="text-2xl">Nouveau Projet</h1>
                        <h2 class="text-lg font-normal text-grey-dark">Configurez votre projet</h2>
                    </div>
                </v-card-title>
                <v-card-text>
                    <v-layout row justify-center align-center>
                        <v-text-field label="Nom" v-model="newProjectForm.name" data-cypress="new-project-name"></v-text-field>

                        <v-tooltip right>
                            <v-icon class="icons"
                                    slot="activator"
                                    color="primary"
                                    dark>help
                            </v-icon>
                            <span>Right tooltip</span>
                        </v-tooltip>
                    </v-layout>

                    <v-layout row justify-center align-center>
                        <v-select :items="environments" item-text="name" item-value="id" v-model="newProjectForm.environment"
                                  label="Environnement"></v-select>

                        <v-tooltip right>
                            <v-icon class="icons"
                                    slot="activator"
                                    color="primary"
                                    dark>help
                            </v-icon>
                            <span>Right tooltip</span>
                        </v-tooltip>
                    </v-layout>

                    <v-layout class="justify-center w-full">
                        <v-btn  color="#e3e3e3" @click="cancelNewProject">Annuler</v-btn>
                        <v-btn color="success" @click="createProject" :loading="projectCreateLoading" data-cypress="new-project-submit">Go !</v-btn>
                    </v-layout>
                </v-card-text>

            </v-card>
        </v-dialog>

        <v-dialog v-model="showNewProjectFromModelDialog" width="400">
            <v-card>
                <v-card-title primary-title justify-center>
                    <h1 class="text-2xl text-center w-full" >Nouveau Projet à partir un modele</h1>
                </v-card-title>
                <v-layout row justify-center align-center>
                    <v-text-field label="code" v-model="projectFromModelForm.code"></v-text-field>

                    <v-tooltip right>
                        <v-icon class="icons"
                                slot="activator"
                                color="primary"
                                dark>help
                        </v-icon>
                        <span>Right tooltip</span>
                    </v-tooltip>
                </v-layout>

                <v-layout row reverse>
                    <v-btn color="success">Créer</v-btn>
                </v-layout>

            </v-card>
        </v-dialog>

        <v-dialog :value="!!deleteProjet" persistent max-width="300px">
            <v-card>
                <v-card-title primary-title justify-center>
                    <h1 class="text-2xl text-center" >Voulez-vous supprimer ce projet ?</h1>
                </v-card-title>
                <v-layout justify-center>
                    <v-btn @click="annulerSuppressionProjet" color="#E3E3E3">Annuler</v-btn>
                    <v-btn color="error" @click="confirmDeleteProject">Ok</v-btn>
                </v-layout>
            </v-card>
        </v-dialog>
        <div class="absolute m-auto"
            style="top: 0; left: 0; right: 0; max-width: 480px; width: 100%; z-index: 1150;"
        >   
            <notification/>
        </div>
    
    </v-app>
</template>

<script>
    import {repositoriesContainer, initProjectRepository} from "../../api"
    import LideLoadingSpinner from "../../components/LideLoadingSpinner";
    import {notify} from '../../event-bus'
    import { error } from 'util';
    import Notification from '../LideNotifications'

    export default {
        name: "Home",
        components: {LideLoadingSpinner, Notification},
        props: {
            environments: {
                type: Array,
                required: false,
                default() {
                    return [
                        {id: 1, name: 'C++'}, //Temporary, should be injected via symfony
                        {id: 3, name: 'Java'},
                    ]
                }
            },
            lideProjectManagerUrl: {
                type: String,
                required: true
            },
            lideWebBaseUrl: {
                type: String,
                required: false,
                default() {
                    return '/app.php/'
                }
            },
            jwt: {
                type: String,
                required: true
            },
            currentUser: {
                type: Object,
                required: true,
            }

        },
        data() {
            return {
                test: false,
                showNewProjectDialog: false,
                showNewProjectFromModelDialog: false,
                
                deleteProjectIndex: null,
                
                projects: [],
                projectsLoaded: false,

                projectFromModelForm: {
                    code: '',
                    nom: '',
                },
                newProjectForm: {
                  name: '',
                  environment: this.environments.length > 0 ? this.environments[0].id : null
                },
                projectCreateLoading: false,
            }
        },
        computed: {
            deleteProjet () {
                return this.projects[this.deleteProjectIndex];
            },
            isUserAdmin () {
                return this.currentUser.roles ? this.currentUser.roles.find(role => role === 'ROLE_ADMIN') : false
            },
            adminUrl () {
                return `${this.lideWebBaseUrl}admin`;
            },
            userProfilePage () {
                return `${this.lideWebBaseUrl}profile`;
            },
            logoutLink () {
                return `${this.lideWebBaseUrl}logout`;
            }
        },
        mounted() {
            //Fetch user projects
            initProjectRepository(this.lideProjectManagerUrl, this.jwt);
            repositoriesContainer.projectRepository.readProjectIndex()
                .then((projects) => {
                    console.log(projects)
                    //Commente la ligne suivante pour voir l'affichage sans projet
                    this.projects = projects.slice() //Make a copy of the array
                    this.projectsLoaded = true;
                    console.log("project loaded")
                }).catch(err => {
                    console.error(err);
                    //TODO get error from response
                    notify("error", "Unable to retrieve projects", 2500);
                })
        },
        methods: {
            openNewProjectDialog() {
                this.showNewProjectDialog = true;
            },

            cancelNewProject() {
                this.showNewProjectDialog = false;
            },

            openNewProjectFromModelDialog() {
                this.showNewProjectFromModelDialog = true;
            },

            deleteProject(index){
                this.deleteProjectIndex = index;
            },

            annulerSuppressionProjet(){
                this.deleteProjectIndex=null;
            },

            confirmDeleteProject(){
                repositoriesContainer.projectRepository.deleteProject(this.deleteProjet.id)
                    .then(response => {
                        notify("success", `Project ${this.deleteProject.name} deleted`)
                        this.projects.splice(this.deleteProjectIndex, 1)
                        this.deleteProjectIndex = null;
                    })
                    .catch(err => {
                        //TODO show error to user
                        console.log(err)
                        notify("error", "Failed to delete project");
                    })
            },

            urlIdeProjet(project){
                return `${this.lideWebBaseUrl}ide/${project.id}`;
            },

            createProject(){
                this.projectCreateLoading = true;
                repositoriesContainer.projectRepository.createProject({
                    name: this.newProjectForm.name,
                    environnement_id: this.newProjectForm.environment,
                    is_public: true
                }).then(project => {
                    this.projects.push(project)
                    this.projectCreateLoading = false;
                    notify("success", "Project successfully created");
                    this.cancelNewProject();
                })
                .catch(err => {
                    console.error(err);
                    this.projectCreateLoading = false;
                    notify("error", "Failed to create project");
                    //TODO error handling
                })
            }

            /*submitNewProjectFromModel() {
              //TODO
            },*/

        }
    }
</script>

<style scoped>

</style>