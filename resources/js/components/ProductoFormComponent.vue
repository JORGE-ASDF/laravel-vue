<template>
    <div class="container mb-5">
        <button class="btn btn-primary float-right mb-5" type="button" data-target="#product-modal" data-toggle="modal">Agregar</button>
        <div id="product-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-4">
                        <h5 class="lead text-center mb-3">Agregar Producto</h5>
                        <div class="row">
                            <div class="col-8">
                                <form @submit.prevent="SaveProduct">
                                    <div class="form-group row">
                                        <label class=" col-form-label col-3">Nombre</label>
                                        <input v-model="nombre" class="form-control col-9" type="text" name="">
                                    </div>
                                    <div class="form-group row">
                                        <label class=" col-form-label col-3">Tipo</label>
                                        <select v-model="id_tipo" class="form-control col-9">
                                            <option v-for="(tipo,index) in tipos" :key="index" :value="tipo.id" v-text="tipo.name"></option>
                                        </select>
                                    </div>
                                    <div class="form-group row">
                                     <label class=" col-form-label col-3">Stock</label>
                                        <input v-model="stock" class="form-control col-9" type="text" name="">
                                    </div>
                                    <div class="form-group row">
                                        <label class=" col-form-label col-3">Precio</label>
                                        <input v-model="precio" class="form-control col-9" type="text" name="">
                                    </div>
                                    <div class="form-group row">
                                        <label class=" col-form-label col-3">Foto</label>
                                        <input v-on:change="onImageChange" type="file" class="form-control-file col-9">
                                    </div>
                                    <div class="row">
                                        <button type="submit" class="btn btn-primary col-3 offset-5">Guardar</button>
                                        <button class="btn btn-danger col-3 ml-1" data-dismiss="modal">cancelar</button>
                                    </div>
                                </form>
                            </div>
                            <div v-if="image" class="col-4 d-flex">
                                <img  :src="image" class=" rounded-circle img" alt="" width="200" height="200">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            image:'',
            tipos:[],
            nombre:"",
            id_tipo:"",
            stock:0,
            precio:0
    }
  },
    mounted(){
        axios
        .get('/tipo/showAll')
        .then(resp=>{
            this.tipos=resp.data.data
        })
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
        SaveProduct(){
            if(this.nombre!="" && this.id_tipo!="" &&
                this.stock!="" && this.precio!="" && this.image!=""){
                axios
                .post('/producto/store',{
                    nombre:this.nombre,
                    precio:this.precio,
                    stock:this.stock,
                    image:this.image,
                    id_tipo:this.id_tipo
                })
                .then(resp=>{
                    if(resp.data){
                        EventBus2.$emit("producto-created", resp.data.data);
                        $('#product-modal').modal('hide');
                        toast.fire({
                            icon: 'success',
                            title: 'Se agrego correctamente',
                        });
                    }else{
                        toast.fire({
                            icon: 'error',
                            title: 'Ah ocurrido un error',
                        });
                    }
                })
                .catch(err=>{
                    console.log(err.response.data);
                })
                }else{
                    toast.fire({
                            icon: 'warning',
                            title: 'No dejes campos Vacios',
                    });
                }
        }
    },
}
</script>
<style lang="scss">
    .img{
        border:solid 1px #000;
    }
</style>
