<template>
	<div>
        <br>
        <div class="row page-titles">
            <div class="col-md-12 col-8 align-self-center">
                <!-- <h3 class="text-themecolor m-b-0 m-t-0">User</h3> -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/electionmonitor/dashboard">Dashboard</router-link></li>
                    <li class="breadcrumb-item active">Questions</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="py-4">              
                <md-button class="btn bg-gray-800" style="color: #FFF;border-radius: 4px" to="/electionmonitor/add-question">New Question</md-button>  
            </div>
            <div class="col-lg-12">
                <div class="mb-4 card">
                    <div class="card-body">

                        <md-field md-clearable class="md-toolbar-section-end">
                                 <v-text-field
                                 dense
                                outlined
                                placeholder="Filter..." 
                                v-model="search" 
                                @input="searchOnTable"
                                clearable
                                prepend-icon="mdi-filter-variant"
                            ></v-text-field>
                                </md-field>
                        <v-data-table
                        :headers="headers"
                        :items="searched"
                        
                        >
                        <!-- <template v-slot:item.results="{ item }">
                            <td>{{ Number(item.results).toLocaleString() }}</td>
                            </template> -->
                        <template v-slot:item.actions="{ item }">
                            <v-icon
                                small
                                class="mr-2"
                                @click="editQuestion(item)"
                            >
                                mdi-pencil
                            </v-icon>
                            <v-icon
                                small
                                @click="deleteQuestion(item)"
                            >
                                mdi-delete
                            </v-icon>
                            </template>
                        </v-data-table>
                      
        
                        <v-dialog
                            v-model="loading"
                            hide-overlay
                            persistent
                            width="300"
                            >
                            <v-card
                                style="background-color: #040539"
                                dark
                            >
                                <v-card-text>
                                Please stand by
                                <v-progress-linear
                                    indeterminate
                                    color="white"
                                    class="mb-0"
                                ></v-progress-linear>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                         <v-row justify="center">
                                        <v-dialog
                                        v-model="deleteDialog"
                                        persistent
                                        max-width="550"
                                        >
                                        
                                        <v-card  style="background-color: #040539; color: #fff">
                                            <v-card-title class="text-h5">
                                            Are you sure you want to delete this question?
                                            </v-card-title>
                                            <v-card-text style="color: #fff">Once this action is performed, it can not be reversed.</v-card-text>
                                            <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                color="white"
                                                text
                                                @click="deleteDialog = false"
                                            >
                                                Cancel
                                            </v-btn>
                                            <v-btn
                                                color="red lighten-1"
                                                text
                                                @click="performDelete()"
                                            >
                                                Delete
                                            </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                        </v-dialog>
                                    </v-row>
                           
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import AspirantForm from './form'
    // import helper from '../../services/helper'
    // import VsudAvatar from "../../components/VsudAvatar.vue";
    // import VsudBadge from "../../components/VsudBadge.vue";
    const toLower = text => {
        return text.toString().toLowerCase()
    }

    const searchByFilter = (items, term) => {
        if (term) {
        return items.filter(

            item => toLower(item.question).includes(toLower(term)) || toLower(item.type).includes(toLower(term))

            )
        }

        return items
        }

    export default {
        // components : { AspirantForm, VsudAvatar, VsudBadge},
        data() {
            return {
                headers: [
                    {
                        text: 'Question',
                        align: 'start',
                        filterable: false,
                        value: 'question',
                    },
                    { text: 'Survey', value: 'survey_id' },
                    { text: 'Type', value: 'type' },
                    
                     { text: 'Options', value: 'frm_option', width: '30%' },
                    { text: 'Date Created', value: 'date_created' },
                    { text: 'Actions', value: 'actions' },
                    ],
                deleteDialog: false,
                deleteQuestionID: null,
                questions: [],
                search: null,
                searched: [],
                filterQuestionForm: {
                    sortBy : 'Question',
                    order: 'desc',
                    description: '',
                    pageLength: 25
                }
            }
        },

        created() {
            this.loading =true
            axios.get('http://172.104.245.14/electionmonitor/api/v1/fetch-survey-questions')
                    .then(response => {
                        this.loading =false
                        for(let i = 0;i < response.data.data.length;i++) {
                            console.log(this.questions.push(response.data.data[i]))
                        }
                        this.searched = this.questions
                        console.log(response)
                    });
        },

        methods: {
            getQuestions(page) {
                this.loading =true
                if (typeof page === 'undefined') {
                    page = 1;
                }
                // let url = helper.getFilterURL(this.filterAspirantForm);
                axios.get('http://172.104.245.14/electionmonitor/api/v1/fetch-survey-questions')
                    .then(response => {
                        
                        for(let i = 0;i < response.data.data.length;i++) {
                            this.questions.push(response.data.data[i])
                        }
                        this.searched = this.questions
                        console.log(this.searched)
                         this.loading = false
                    });
            },
            
            deleteQuestion(question) {
                this.deleteDialog = true
                this.deleteQuestionID = question.id
            }, 
          
            performDelete (){
                this.loading =true
                axios.delete('http://172.104.245.14/electionmonitor/api/v1/delete-question/'+this.deleteQuestionID).then(response => {
                     // toastr['success'](response.data.message);
                    this.loading = false
                    this.deleteDialog = true
                    // console.log(response)
                    this.$router.go()
                    this.searched = this.question
                }).catch(error => {
                    // toastr['error'](error.response.data.message);
                    this.loading = false
                });
            },
            searchOnTable () {
                this.searched = searchByFilter(this.questions, this.search)
            },
            editQuestion(question){
                this.$router.push('/electionmonitor/edit-question/'+question.id+'/edit');
            },
       
        },
        filters: {
          moment(date) {
            return helper.formatDate(date);
          }
        }
    }
</script>
