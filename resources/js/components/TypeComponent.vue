<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Fecha de creacion</th>
                            <th scope="col">Fecha de actualizacion</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody v-for="(tipo, index) in tipos" :key="index">
                        <tr>
                            <th scope="row" v-text="tipo.id"></th>
                            <td v-text="tipo.name"></td>
                            <td v-text="tipo.created_at"></td>
                            <td v-text="tipo.updated_at"></td>
                            <td>
                                <button @click="Edit(tipo.id)" class="btn btn-sm btn-info" data-target="#my-modal-edit" data-toggle="modal">Editar</button>
                                <button @click="Delete(tipo.id)" class="btn btn-sm btn-danger">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
               <div class="row mt-5">
                   <div class="col-md-8">
                       <nav aria-label="Page navigation">
                           <ul class="pagination">
                               <li v-bind:class="{disabled:!pagination.first_link}" class="page-item" aria-current="page">
                                   <a class="page-link" href="#" @click="GetResult(pagination.first_link)">&laquo;</a>
                               </li>
                                <li v-bind:class="{disabled:!pagination.prev_link}" class="page-item" aria-current="page">
                                   <a class="page-link" href="#" @click="GetResult(pagination.prev_link)">&lt;</a>
                               </li>
                               <li v-for="n in pagination.last_page" :key="n" v-bind:class="{active: pagination.current_page == n}" class="page-item" aria-current="page">
                                   <a class="page-link" href="#" @click="GetResult(pagination.path_page + n)">{{n}}</a>
                               </li>
                               <li v-bind:class="{disabled:!pagination.next_link}" class="page-item" aria-current="page">
                                   <a class="page-link" href="#" @click="GetResult(pagination.next_link)">&gt;</a>
                               </li>
                               <li v-bind:class="{disabled:!pagination.last_link}" class="page-item" aria-current="page">
                                   <a class="page-link" href="#" @click="GetResult(pagination.last_link)">&raquo;</a>
                               </li>
                           </ul>
                       </nav>
                   </div>
                   <div class="col-md-4">
                   Pagina:{{pagination.from_page}} - {{pagination.to_page}}
                   Total: {{pagination.total_pages}}
                   </div>
               </div>
            </div>
        </div>
        <div id="my-modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content p-3">
                <div class="modal-body">
                    <p>Editar Tipo de comida</p>
                    <form @submit.prevent="Update(tipo.id)">
                     <div class="form-group row">
                            <label class=" col-form-label col-4" for="my-input">Nombre</label>
                            <div class="col-8">
                                <input v-bind:value="tipo.name" id="name" readonly class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class=" col-form-label col-4" for="my-input"> Nuevo Nombre</label>
                            <div class="col-8">
                                <input id="name" v-model="name" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <button type="submit"  class="btn btn-primary col-3 offset-3">Guardar</button>
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
            id:"",
            name:"",
            tipo:[],
            tipos: [],
            pagination:[]

        };
    },
    mounted() {
        this.GetResult('/tipo/show');
        EventBus.$on("tipo-created", tipo => {
            this.tipos.unshift(tipo);
        });
    },
    methods:{
        GetResult(url){
            axios
            .get(url)
            .then(({data:datos})=>{
                this.tipos = datos.data
               this.pagination={
                current_page:datos.meta.current_page,
                last_page:datos.meta.last_page,
                from_page:datos.meta.from,
                to_page:datos.meta.to,
                total_pages:datos.meta.total,
                path_page:datos.meta.path+"?page=",
                first_link:datos.links.first,
                last_link:datos.links.last,
                prev_link:datos.links.prev,
                next_link:datos.links.next
        };
            })
            .catch(err=>{
            console.log(err.response.data);
            })
        },
        Edit(id){
            axios
            .get('/tipo/edit/'+id)
            .then(resp=>{
                this.tipo = resp.data
            })
            .catch(err=>{
                console.log(err.response.data);
            })
        },
        Update(id){
            if(this.name!=""){
                axios
                .put('/tipo/update/'+id,{id:id,name:this.name})
                .then(resp=>{
                    toast.fire({
                        icon: 'success',
                        title: 'Se edito correctamente',
                    });
                this.GetResult('/tipo/show');
                $('#my-modal-edit').modal('hide');
                this.name=""
                })
                .catch(err=>{
                    console.log(err.response.data)
                })
            }else{
                toast.fire({
                    icon: 'warning',
                    title: 'no dejes campos vacios',
                });
            }
        },
        Delete(id){
            axios
            .delete('/tipo/destroy/'+id,{id:id})
            .then(resp=>{
                toast.fire({
                        icon: 'success',
                        title: 'Se elimino correctamente',
                    });
                this.GetResult('/tipo/show');
            })
        }
    }
};
</script>
<style lang="scss" scoped>
    a{
        cursor:pointer;
    }
    button{
        color:#fff;
    }
</style>
