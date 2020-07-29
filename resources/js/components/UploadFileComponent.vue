<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">File Upload Component</div>
                    <div class="card-body">
                       <div class="row">
                       <form @submit.prevent="uploadImage">
                       <div class="col-md-3" v-if="image">
                              <img :src="image" class="img-responsive" height="70" width="90">
                           </div>
                          <div class="col-md-6">
                              <input type="file" v-on:change="onImageChange" class="form-control">
                          </div>
                           <div class="col-md-6">
                              <input type="text" v-model="descripcion" class="form-control">
                          </div>
                          <div class="col-md-3">
                             <button type="submit" class="btn btn-success btn-block">Upload Image</button>
                          </div>
                       </form>

                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                image: '',
                descripcion:''
            }
        },
        methods: {
            onImageChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            uploadImage(){
                axios.post('/image/store',{image: this.image,descripcion:this.descripcion}).then(response => {
                   if (response.data.success) {
                     alert(response.data.success);
                   }
                });
            }
        }
    }
</script>
