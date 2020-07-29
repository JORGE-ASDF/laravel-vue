<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Tipo de comida</th>
                            <th scope="col">Fecha de creacion</th>
                            <th scope="col">Fecha de actualizacion</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(producto,index) in productos" :key="index">
                            <th scope="row">{{producto.id}}</th>
                            <td><img :src="'/images/'+producto.image" width="80" height="80"></td>
                            <td>{{producto.nombre}}</td>
                            <td>$ {{producto.precio}}</td>
                            <td>{{producto.stock}}</td>
                            <td>{{producto.tipo}}</td>
                            <td>{{producto.created_at}}</td>
                            <td>{{producto.updated_at}}</td>
                            <td>
                                <button @click="Edit(producto.id)"  class="btn btn-sm btn-info text-white" data-target="#product-modal-edit" data-toggle="modal">Editar</button>
                                <button @click="Destroy(producto.id)" class="btn btn-sm btn-danger">Eliminar</button>
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
         <div id="product-modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-4">
                        <h5 class="lead text-center mb-3">Editar Producto</h5>
                        <div class="row">
                            <div class="col-8" v-if="productoEdit">
                                <form @submit.prevent="UpdateProduct(productoEdit.id)">
                                    <div class="form-group row">
                                        <label class=" col-form-label col-3">Nombre</label>
                                        <input id="nombre" :value="productoEdit.nombre" class="form-control col-9" type="text" name="">
                                    </div>
                                    <div class="form-group row">
                                        <label class=" col-form-label col-3">Tipo</label>
                                        <select id="id_tipo" class="form-control col-9">
                                        <option :value="productoEdit.id_tipo" selected>{{productoEdit.tipo}}</option>
                                            <option v-for="(tipo,index) in tipos" :key="index" :value="tipo.id" v-text="tipo.name"></option>
                                        </select>
                                    </div>
                                    <div class="form-group row">
                                     <label class=" col-form-label col-3">Stock</label>
                                        <input id="stock" :value="productoEdit.stock" class="form-control col-9" type="text" name="">
                                    </div>
                                    <div class="form-group row">
                                        <label class=" col-form-label col-3">Precio</label>
                                        <input id="precio" :value="productoEdit.precio" class="form-control col-9" type="text" name="">
                                    </div>
                                    <div class="row">
                                        <button type="submit" class="btn btn-primary col-3 offset-5">Editar</button>
                                        <button class="btn btn-danger col-3 ml-1" data-dismiss="modal">cancelar</button>
                                    </div>
                                </form>
                            </div>
                            <div   class="col-4 d-flex">
                                <img v-if="productoEdit" :src="'images/'+productoEdit.image" class=" rounded-circle img" alt="" width="200" height="200">
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
data(){
    return{
        tipos:[],
        productoEdit:[],
        productos:[],
        pagination:[],
        nombre:"",
        id_tipo:"",
        id:"",
        stock:0,
        precio:0
    }
},
mounted() {
    this.GetTipos();
    this.GetResult('/producto/show');
    EventBus2.$on("producto-created", producto => {
            this.productos.unshift(producto);
    });
},
methods:{
    GetTipos(){
         axios
        .get('/tipo/show')
        .then(resp=>{
            this.tipos=resp.data.data
        })
    },
    GetProducto(url){
         axios
        .get('/producto/edit')
        .then(resp=>{
            this.tipos=resp.data.data
        })
    },
    GetResult(url){
        axios
    .get('/producto/show')
    .then(({data:datos})=>{
        this.productos = datos.data
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
        console.log(err.response.data)
    })
    },
    Edit(id){
        axios
        .get('/producto/edit/'+id)
        .then(resp=>{
            console.log(this.productoEdit=resp.data.data);
        })
        .catch(err=>{
            console.log(err.response.data)
        })
    },
    UpdateProduct(id){
        this.nombre = document.getElementById('nombre').value;
        this.id_tipo=document.getElementById('id_tipo').value;
        this.stock=document.getElementById('stock').value;
        this.precio=document.getElementById('precio').value;
        if(this.nombre!="" && this.id_tipo!="" && this.stock!="" && this.precio!=""){
            axios
            .put('/producto/update/'+id,{
                id:id,
                nombre:this.nombre,
                id_tipo:this.id_tipo,
                stock:this.stock,
                precio:this.precio
            })
            .then(resp=>{
                this.GetResult('/producto/show');
                $('#product-modal-edit').modal('hide');
                toast.fire({
                    icon: 'success',
                    title: 'Se Actualizo correctamente',
                });
            })
            .catch(err=>{
                console.log(err.response.data);
            })
        }
    },
    Destroy(id){
        axios
        .delete('/producto/destroy/'+id,{
            id:id
        })
        .then(resp=>{
            this.GetResult('/producto/show');
            toast.fire({
                icon: 'success',
                title: 'Se Elimino correctamente',
            });
        })
        .catch(err=>{
            console.log(err.response.data);
        })
    }
}
}
</script>
<style lang="scss">

</style>
