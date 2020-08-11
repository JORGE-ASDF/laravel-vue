<template>
<div class="row">
            <div class="col-md-8 offset-2">
                <div class="card">
                    <div class="card-body">
                        <button class="btn close" @click="showDetail=false" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="card-title">{{detail.nombre}}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li>{{detail.precio}}</li>
                                    <li>Categoria: {{detail.tipo}}</li>
                                    <br>
                                </ul>
                                <button class="btn btn-success btn-sm" @click="addCart(detail)">Ordenar</button>
                                <div class="mt-5" v-if="!myRating.length">
                                    <p class="text-muted text-small">Puedes opinar acerca de este platillo</p>
                                    <star-rating :star-size="30" v-model="rating" :increment="0.5" text-class="custom-text" ></star-rating>
                                    <textarea placeholder="Escribe un comentario acerca de este producto" v-model="comment" name="comment" class="form-control mt-2 mb-2" id="comment" cols="4" rows="3"></textarea>
                                    <button @click="setRating(detail.id)" class="btn btn-primary btn-sm d-flex">Votar</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <img :src="'/images/'+detail.image" class=" img-thumbnail" alt="">
                            </div>
                        </div>
                    <div>
                        <p class="lead text-center text-muted">Comentarios acerca del producto</p>
                            <div class="row mb-3" v-for="comment in AllRating" :key="comment.id">
                                <div class="col-md-1">
                                    <img :src="'/images/'+comment.avatar" class="rounded-circle" alt="no-image" width="50" height="50">
                                </div>
                                <div class="col-md-11 pl-3 rating">
                                    <p class="text-info">{{comment.user}}
                                    <star-rating :star-size="10" :read-only="true" :rating="comment.rating"></star-rating>
                                    <p class="text-justify text-muted text-small">{{comment.comment}}</p>
                                </div>
                                <hr>
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
            detail:{},
             //se inicializa el rating del producto
        rating:0,
        //variable de comentario del rating
        comment:'',
        //obtiene el rating del usuario loggeado
        myRating:[],
        //obtiene todos los ratings del producto
        AllRating:[]
        }
    },
    mounted(){

    },
    methods:{
         //metodo para ver el detalle del producto
    Detail(id){
        //se ejecuta el metodo para obtener el rating
        this.ShowRating(id);
        //se ejecuta el metodo para obtener el rating del usuario loggeado
        this.MyRating(id);
        //se recibe un get
        axios
        .get('/producto/detail/'+id)
        //respuesta de laravel
        .then(resp=>{
            //la variable recibe el arreglo de datos que envia laravel
            this.detail=resp.data.data;
            //se muestra la seccion de detalle
            this.showDetail=true;
        })
        //en caso de error
        .catch(err=>{
            //se muestra el mensaje de error
            console.log(err.response.data)
        })
    },
     setRating(id){
        //se envia por axios
        axios
        .post('/rating/new',{product_id:id,comment:this.comment,rating:this.rating})
        //respuesta de que se recibe
        .then(resp=>{
            //se ejecuta el metodo para mostrar todos los rating
            this.ShowRating(id);
            //se ejecuta el metodo para mostrar el rating del usuario loggeado
            this.MyRating(id);
        })
     }, //metodo que obtiene el rating del producto seleccionado
    ShowRating(id){
        //se envia por axios el id por post a laravel
        axios
        .post('/rating/all',{id:id})
        //respuesta de laravel
        .then(({data:datos})=>{
            //la varaiabel recibe el arreglo de datos
            this.AllRating = datos.data;
        })
        //en caso de error
        .catch(err=>{
            //se muestra el mensaje de error
            console.log(err.response.data)
        })
    },
    //metodo para obtener el rating del usuario loggeado
    MyRating(id){
        //se envia un post con axios a laravel con el id del producto
        axios
        .post('/rating/show',{id:id})
        //respuesta de laravel
        .then(({data:datos})=>{
            //la variable recibe el arreglo de datos
            this.myRating = datos.data;
        })
        //en caso de error
       .catch(err=>{
           //se muestra el mensaje de error
            console.log(err.response.data)
        })
    }
    }
}
</script>

<style lang="css">
.rating{
    border:2px solid #000;
    border-bottom:1px solid #eee;
}
</style>
