<template>
<div class="container mb-5">
<button class="btn btn-primary float-right " type="button" data-target="#my-modal" data-toggle="modal">Agregar</button>
    <div id="my-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content p-3">
                <div class="modal-body">
                    <p>Agregar Tipo de comida</p>
                    <form @submit.prevent="submit">
                        <div class="form-group row">
                            <label class=" col-form-label col-3" for="my-input">Nombre</label>
                            <div class="col-9">
                                <input id="name" v-model="name" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary col-3 offset-3">Guardar</button>
                            <button class="btn btn-danger col-3 ml-1" data-dismiss="modal">cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
    data(){
        return{
            name:""
        }
    },
    mounted:{},
    methods:{
        submit() {
         if(this.name!= ""){
             axios
        .post("/tipo/store", { name: this.name })
        .then(resp => {
          EventBus.$emit("tipo-created", resp.data.data);
          this.name = "";
toast.fire({
            icon: 'success',
            title: 'Se agrego correctamente',
          });
          $('#my-modal').modal('hide');
          event.target.reset();

        })
        .catch(err => {
          console.log(err.response.data);
        });
         }else{
        toast.fire({
            icon: 'error',
            title: 'campos vacios',
          });
         }
        }
    }
}

</script>
