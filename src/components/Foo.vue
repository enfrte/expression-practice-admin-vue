<template>
    <div id="foo" class="foo">
        <div v-if="jsonData">
            <div v-for="data in jsonData" :key="data._id">
                <h1>{{ data.title }}</h1>
                <label for="">Title </label><input :id="'title_'+data._id" type="text" v-model="data.title" placeholder="Edit me">
            </div>
        </div>
        <div v-else><h1>Data not loaded. Check if Apache is running</h1></div>

        <div>
            <button type="button" v-on:click="addLesson">New Lesson</button>
            <button type="button" v-on:click="submitForm">Submit</button>
            <button type="button" v-on:click="getLatestDatabase">Get Latest</button>
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
            addLesson() {
                this.jsonData.push(
                    {
                        "_id": this.generateUUID(), 
                        "title": "",
                        /* to add
                        "inProduction":true,
                        "slug":"verb-chaining",
                        */
                    }
                );
                //console.log("Lesson added", this.jsonData);
            },
            submitForm() {
                // Submit for changes to the backend - PHP will process it and save a copy
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

</style>
