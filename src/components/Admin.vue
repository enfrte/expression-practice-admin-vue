<template>
    <div id="Admin" class="admin container p-4">

        <div class="menu-container">
            <div class="menu">
                <button class="btn btn-success m-1" type="button" v-on:click="addLesson">Add lesson</button>
                <button class="btn btn-success m-1" type="button" v-on:click="submitForm">Save all</button>
            </div>
        </div>

        <div v-if="jsonData" class="">
            <div v-for="data in jsonData" :key="data._id" class="card mt-4">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-10 offset-sm-2">
                            <h2 class="card-title">{{ data.title }}</h2>
                        </div>
                    </div>
                    <!-- Publish -->
                    <div class="form-group row">
                        <div class="col-md-2">Publish</div>
                        <div class="col-md-10">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" :id="'inProduction_'+data._id" v-model="data.inProduction">
                                <label class="form-check-label" :for="'inProduction_'+data._id">{{data.inProduction ? 'Published' : 'Unpublished'}} </label>
                            </div>
                        </div>
                    </div>
                    <!-- Title -->
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Title </label>
                        <div class="col-md-10">
                            <input :id="'title_'+data._id" type="text" v-model="data.title" class="form-control" placeholder="Edit me">
                        </div>
                    </div>
                    <!-- Slug -->
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Slug </label>
                        <div class="col-md-10">
                            <input v-on:input="slugCheck($event, data)" :value="data.slug" :id="'slug_'+data._id" type="text" class="form-control"   placeholder="Slug link">
                        </div>
                    </div>
                    <!-- Tutorial -->
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Tutorial </label>
                        <div class="col-md-10">
                            <textarea :id="'Tutorial_'+data._id" v-model="data.tutorial" class="form-control textarea-tutorial" placeholder="Markdown supported"></textarea>
                        </div>
                    </div>
                    <!-- Questions -->
                    <div v-for="(question, index) in data.questions" :key="question._id" class="mt-4">
                            <h4>Question {{ index + 1 }}</h4>
                            <div v-for="(en, index) in question.en" :key="'en_' + index" class="form-group row">
                            <label class="col-md-2 col-form-label">English </label>
                            <div class="col-md-10 input-group">
                                <input v-model="question.en[index]" type="text" class="form-control">
                                <span class="input-group-btn"><button class="btn btn-danger delete-btn" type="button">X</button></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10 offset-md-2">
                                <button class="btn btn-warning" type="button">Add Translation</button>
                            </div>
                        </div>
                        <div v-for="(fi, index) in question.fi" :key="'fi_' + index" class="form-group row">
                            <label class="col-md-2 col-form-label">Finnish </label>
                            <div class="col-md-10 input-group">
                                <input v-model="question.fi[index]" type="text" class="form-control">
                                <span class="input-group-btn"><button class="btn btn-danger delete-btn" type="button">X</button></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10 offset-md-2">
                                <button class="btn btn-warning" type="button">Add Translation</button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10 offset-md-2">
                                <button class="btn btn-danger" type="button">Delete question</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- Add question button -->
                    <div class="form-group row">
                        <div class="col-md-10 offset-sm-2">
                            <button :id="'addQuestion_'+data._id" class="btn btn-success" type="button" v-on:click="addQuestion">Add question</button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div v-else><h3 class="text-warning">Data is loading, or data has not loaded. <br>Check if Apache is running</h3></div>

    </div>
</template>

<script>

    export default {
        data() {
            return {
                jsonData: false,
            }
        },
        computed: {

        },
        async created() {
            const databaseFile = await this.getLatestDatabase();
            const jsonDataModule = await import("../../backend/database/" + databaseFile);
            this.jsonData = jsonDataModule.default;
            //console.log(this.jsonData);
        },
        methods: {
            slugCheck (e, data) {
                const slugText = e.target.value
                slugId = slugId.replace('slug_','')
                // Find which object this checkbox belongs to
                const dataObjIndex = this.jsonData.findIndex((val) => {
                    return val._id === slugId
                });
                //this.jsonData[dataObjIndex].slug = slugText
                //console.log("dataObj", dataObjIndex); 
                const a = 'àáäâèéëêìíïîòóöôùúüûñçßÿỳýœæŕśńṕẃǵǹḿǘẍźḧ'
                const b = 'aaaaeeeeiiiioooouuuuncsyyyoarsnpwgnmuxzh'
                const p = new RegExp(a.split('').join('|'), 'g')

                data.slug = slugText.toString().toLowerCase()
                    .replace(/[\s_]+/g, '-')
                    .replace(p, c =>
                    b.charAt(a.indexOf(c)))
                    .replace(/&/g, `-and-`)
                    .replace(/[^\w-]+/g, '')
                    .replace(/--+/g, '-')
                    .replace(/^-+|-+$/g, '')
                //console.log("jsonData", this.jsonData);
            },
            addQuestion() {
                console.log("To do: addQuestion()");
            },
            addLesson() {
                this.jsonData.push(
                    {
                        "_id": this.generateUUID(), 
                        "title": "",
                        "inProduction": false,
                        "slug": "",
                        "tutorial": "",
                        "lang": "Finnish", // hard coded for now, but in futire set from a config file
                        "questions": [], // an array of objects like this - { "en": [""], "fi": [""], "_id": "" }
                    }
                );
                //console.log("Lesson added", this.jsonData);
            },
            submitForm() {
                // Submit changes to the backend - PHP will process it and save a copy
                // Requires Apache server running on port 80
                // https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch
                fetch('http://localhost/expression-practice-admin-vue/backend/saveData.php', {
                    method: 'POST', 
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(this.jsonData),
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            },
            generateUUID() { 
                // https://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid
                var d = new Date().getTime(); //Timestamp
                var d2 = (performance && performance.now && (performance.now()*1000)) || 0; // Time in microseconds since page-load or 0 if unsupported
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random() * 16; // random number between 0 and 16
                    if(d > 0){ // Use timestamp until depleted
                        r = (d + r)%16 | 0;
                        d = Math.floor(d/16);
                    } else { // Use microseconds since page-load if supported
                        r = (d2 + r)%16 | 0;
                        d2 = Math.floor(d2/16);
                    }
                    return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
                });
            },
            getLatestDatabase() {
                return fetch('http://localhost/expression-practice-admin-vue/backend/latestDatabase.php')
                    .then(response => response.text())
                    .then(data => data)
                    .catch((error) => console.error('Fetch error (is the server connected?):', error));
            },
        }
    }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .admin {
        background-color: #333;
        max-width: 800px;
        margin-top: 50px; 
    }

    .textarea-tutorial-container {
        max-width: 600px;
        margin: auto;
    }

    .form-elements-container {
        max-width: 600px;
    }

    .textarea-tutorial {
        height: 400px;
    }

    .menu-container {
        height: 50px;
        position: fixed;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        text-align: center;
        z-index: 1;
        max-width: 800px;
        background-color: whitesmoke;
    }

    .delete-btn {
        margin-left: 5px;
    }
</style>
