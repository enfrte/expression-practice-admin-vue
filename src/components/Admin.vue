<template>
    <div id="Admin" class="admin container p-4">

        <div v-if="jsonData" class="">
            <div v-for="data in jsonData" :key="data._id" class="card mt-4">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <h2 class="card-title">{{ data.title }}</h2>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">Checkbox</div>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" :id="'inProduction_'+data._id" v-model="data.inProduction">
                                <label class="form-check-label" for="">Published </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Title </label>
                        <div class="col-sm-10">
                            <input :id="'title_'+data._id" type="text" v-model="data.title" class="form-control" placeholder="Edit me">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Slug </label>
                        <div class="col-sm-10">
                            <input v-on:keyup="slugCheck" :value="data.slug" :id="'slug_'+data._id" type="text" class="form-control"   placeholder="Slug link">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Tutorial </label>
                        <div class="col-sm-10">
                            <textarea :id="'Tutorial_'+data._id" v-model="data.tutorial" class="form-control textarea-tutorial" placeholder="Markdown supported"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button :id="'addQuestion_'+data._id" class="btn btn-primary" type="button" v-on:click="addQuestion">Add question</button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div v-else><h3 class="text-warning">Data is loading, or data has not loaded. <br>Check if Apache is running</h3></div>

        <div class="mt-4">
            <button class="btn btn-primary m-1" type="button" v-on:click="addLesson">Add lesson</button>
            <button class="btn btn-primary m-1" type="button" v-on:click="submitForm">Save all</button>
        </div>

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
            slugCheck (e) {
                let slugId = e.target.id
                const slugText = e.target.value
                slugId = slugId.replace('slug_','')
                const dataObjIndex = this.jsonData.findIndex((val) => {
                    return val._id === slugId
                });
                //this.jsonData[dataObjIndex].slug = slugText
                //console.log("dataObj", dataObjIndex); 
                const a = 'àáäâèéëêìíïîòóöôùúüûñçßÿỳýœæŕśńṕẃǵǹḿǘẍźḧ'
                const b = 'aaaaeeeeiiiioooouuuuncsyyyoarsnpwgnmuxzh'
                const p = new RegExp(a.split('').join('|'), 'g')

                this.jsonData[dataObjIndex].slug = slugText.toString().toLowerCase()
                    .replace(/[\s_]+/g, '-')
                    .replace(p, c =>
                    b.charAt(a.indexOf(c)))
                    .replace(/&/g, `-and-`)
                    .replace(/[^\w-]+/g, '')
                    .replace(/--+/g, '-')
                    .replace(/^-+|-+$/g, '')
                console.log("jsonData", this.jsonData);
            },
            addQuestion() {
                console.log("addQuestion called");
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
</style>
