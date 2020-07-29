<template>
    <div class="container mt-4">
    <button class="btn float-right mb-5" data-target="#myModalRight" data-toggle="modal">
        <i class="fa fa-shopping-cart" style="font-size:30px"></i>
        <span class="float-left badge badge-info text-white">{{badge}}</span>
    </button>
    <br><br>
    <nav v-if="showDetail===false" aria-label="Page breadcrumb">
        <ol class="breadcrumb">
            <li v-for="type in types" :key="type.id" class="breadcrumb-item active" aria-current="page">
                <a href="#" @click="GetProducts(type.id)">{{type.name}}</a>
            </li>
        </ol>
    </nav>
    <div v-if="showProducts===true && showDetail===false" class="row">
        <p class="lead" v-if="!products.length">No hay productos para mostrar</p>
        <div class="col-3"  v-for="prod in products" :key="prod.id">
            <div class="card card-custom bg-white border-white border-0">
                <div class="card-custom-img" style="background-image: url(https://image.freepik.com/foto-gratis/pano-cocina-mesa-madera_74190-7693.jpg);">
                </div>
                <div class="card-custom-avatar">
                    <img class="img-fluid" :src="'/images/'+prod.image" />
                </div>
                <div class="card-body" style="overflow-y: auto">
                    <h4 class="card-title">{{prod.nombre}}</h4>
                    <p class="card-text">Precio: <span class="badge badge-success">${{prod.precio}}</span></p>
                    <p class="text-primary">Categorias: <span class="text-small text-dark">{{prod.tipo}}</span></p>
                </div>
                <div class="card-footer" style="background: inherit; border-color: inherit;">
                    <a role="button" @click="addCart(prod)" class="btn btn-sm btn-success text-white">Ordenar</a>
                    <a href="#" @click="Detail(prod.id)" class="btn-sm btn btn-primary">Ver Mas</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row" v-if="showDetail===true">
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
                            <div class="col-md-11 pl-3">
                                <p class="text-info">{{comment.user}}
                                <star-rating :star-size="15" :read-only="true" :rating="comment.rating"></star-rating>
                                <p class="text-justify text-muted text-small">{{comment.comment}}</p>
                            </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModalRight" class="modal fade modal-right" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal right</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class=" table table-striped text-left">
                        <tbody>
                            <tr v-for="(cart) in  carts" :key="cart.id">
                                <td>
                                    <img :src="'/images/'+cart.image" alt="" width="50" height="50">
                                </td>
                                <td>{{cart.nombre}}</td>
                                <td>{{cart.precio}}</td>
                                <td>{{cart.cantidad}}</td>
                                <td>
                                    <button @click="removeCart(cart)" class="btn btn-danger btn-sm">X</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    Total: ${{total}} &nbsp;
                    <button @click="SaveOrder(carts)" class="btn btn-sm btn-success">
                        checkout
                    </button>
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
            //almacena el arreglo de producto que envia laravel
            products:[],
            //almacena los tipos de comida que envia laravel
            types:[],
            //se usa para mostrar y ocultar la lista de productos
            showProducts:false,
            //almacena la cantidad de productos en el carrito
            badge:'0',
            //almacena los productos del carrito
            carts:[],
            //variable temporal para almacenar el producto seleccionado
            cartAdd:{
                id:'',
                nombre:'',
                precio:'',
                cantidad:1,
                image:"",
            },
            //variable que inicializa la cantidad del producto en el carrito
            quantity:1,
            //almacena el total de la orden
            total:'0',
            //se usa para mostrar y ocultar el detalle del producto
            showDetail:false,
            //se usa para mostrar y ocultar el rating del producto
            getRating:false,
            //almacena el arreglo que envia laravel del detalle del producto
            detail:[],
            //inicializa el rating del producto
            rating:0,
            //almacena el comentario del producto que se envia a laravel
            comment:'',
            //almacena el rating del producto del usuario loggueado
            myRating:[],
            //almacena todos los ratings que envia laravel
            AllRating:[]
        }
    },
    mounted(){
        //metodo para obtener los tipos de comida al inicializar el componente
        this.GetTypes('/product/getTypes');
        //metodo para obtener los productos del carrito al inicializar el componente
        this.viewCart();
    },
    methods: {
        //Metodo para guardar la orden en la base de datos
        SaveOrder(){
            //variable que contiene cada arreglo de productos
            let data = {
                //igualamos la data a la variable de carts que contiene la orden
                data:this.carts
            };
            //metodo de axios que envia por post la informacion
            axios
            .post('/order/store',{data})
            //respuesta de el envio de datos
            .then(resp=>{
                //la variable que contenia los productos del carrito de vacia
                this.carts = [];
                //la variable que tenia el total se vacia
                this.total='0';
                //la variable que tenia la cantidad de productos en el carrito se vacia
                this.badge='0';
                //el modal de el carrito se cierra
                $('#cart-modal').modal('hide');
                //se vacia el localstorage
                localStorage.removeItem('carts');
                //se muestra una alerta
                toast.fire({
                    icon: 'success',
                    title: 'Se orden a sido registrada ',
                });
            })
            //en caso de que un error ocurra
            .catch(err=>{
                //se muestra el error por consola
                console.log(err.response.data);
            })
        },
        //metodo para agregar un producto al carrito
        addCart(prod){
            /*variable que se creo para buscar el id del
            producto que se acaba de agregar para evitar
            productos repetidos*/
            var f =_.find(this.carts,['id',prod.id]);
            //se verifica si el id existe en el carrito
            if(typeof f == 'object'){
                /*se crea una variable que almacena la posicion
                del producto repetido*/
                var index = _.indexOf(this.carts,f)
                /*se incrementa la cantidad del producto
                repetido en el carrito*/
                this.carts[index].cantidad++
                //se muestra una alerta de exito
                toast.fire({
                    icon: 'success',
                    title: 'Su orden se ah actualizado',
                });
                /*se ejecuta el metodo para guardar
                el producto en el carrito*/
                this.storeCart();
                /*se ejecuta el metodo para ver
                todos los productos del carrito*/
                this.viewCart();
            //si el producto no esta repetido
            }else{
                /*se guarda el id el producto en
                el objeto cartAdd*/
                this.cartAdd.id = prod.id;
                 /*se guarda el nombre el producto en
                el objeto cartAdd*/
                this.cartAdd.nombre = prod.nombre;
                 /*se guarda el precio el producto en
                el objeto cartAdd*/
                this.cartAdd.precio = prod.precio;
                /*se iguala la cantidad del objeto
                con la variable de cantidad*/
                this.cartAdd.cantidad = this.quantity;
                 /*se guarda la images del producto en
                el objeto cartAdd*/
                this.cartAdd.image = prod.image;
                //se comprueba que el producto se agrego
                if(this.carts.push(this.cartAdd)){
                    //se muestra una alerta de exito
                    toast.fire({
                        icon: 'success',
                        title: 'Se agrego a la orden ',
                    });
                }
                //Se vacia el objeto cartAdd
                this.cartAdd={};
                /*se ejecuta el metodo para guardar
                el producto en el carrito*/
                this.storeCart();
                /*se ejecuta el metodo para ver
                todos los productos del carrito*/
                this.viewCart();
            }
        },
        removeCart(cart){
            /*variable que se creo para buscar el id del
            producto que se eliminara del carrito*/
            var f =_.find(this.carts,['id',cart.id]);
            //se compreba que el id exista
            if(typeof f == 'object'){
                //variable que almacena la pocision del producto repetido
                var index = _.indexOf(this.carts,f)
                //se verifica que el producto a eliminar sea menor o igual a uno
                if(this.carts[index].cantidad <= 1){
                    //se elimina del arreglo de productos en el carrito
                    this.carts.splice(cart,1);
                    //se ejecuta el metodo que guarda el carrito para refrescar
                    this.storeCart();
                //si la cantidad es mayor a uno
                }else{
                    //la cantidad del producto disminuye uno
                    this.carts[index].cantidad--
                    //se ejecuta el metodo que guarda el carrito para refrescar
                    this.storeCart();
                }
            }
        },
        //metodo que guarda el producto seleccionado en el carrito
        storeCart(){
            //variable que almacena el arreglo del producto convertido
            let parsed = JSON.stringify(this.carts);
            //se agrega el producto al carrito
            localStorage.setItem('carts',parsed);
            //se ejecuta el metodo para actualizar el carrito
            this.viewCart();
        },
        //metodo que recibe el id del producto para obtener el detalle
        Detail(id){
            //se ejecuta el metodo para obtener el rating de producto con el id
            this.ShowRating(id);
            //se ejecuta el metodo para obtener el rating del usuario loggeado
            this.MyRating(id);
            //se envia a axios
            axios
            .get('/producto/detail/'+id)
            //respuesta de axios
            .then(resp=>{
                //se almacena en el arreglo de detalle la data que envia laravel
                this.detail=resp.data.data;
                //se muestra el apartado de detalle y se oculta la lista de productos
                this.showDetail=true;
            })
            //si ocurre un error
            .catch(err=>{
                //se muestra el error por consola
                console.log(err.response.data)
            })
        },
        //metodo que recibe el id de un producto para obtener el rating
        ShowRating(id){
            //se envia un post por axios con el id
            axios
            .post('/rating/all',{id:id})
            //respuesta de axios
            .then(({data:datos})=>{
                //se iguala el arreglo con la data que envia laravel
                this.AllRating = datos.data;
            })
            //si ocurre un error
            .catch(err=>{
                //se muestra por consola
                console.log(err.response.data)
            })
        },
        //metodo que recibe un id para obtener el rating del usuario loggeado
        MyRating(id){
            //se envia un post por axios
            axios
            .post('/rating/show',{id:id})
            //respuesta de axios
            .then(({data:datos})=>{
                //se iguala el arreglo con la data que envia laravel
                this.myRating = datos.data;
            })
            //en caso de error
            .catch(err=>{
                //se muestra por consola el error
                console.log(err.response.data)
            })
        },
        //metodo para obtener los productos del carrito
        viewCart(){
            //se verifica que local storage tenga productos en el carrito
            if(localStorage.getItem('carts')){
                //arreglo que almacena los productos del carrito
                this.carts = JSON.parse(localStorage.getItem('carts'));
                //se actualiza la cantidad del badge
                this.badge = this.carts.length;
                //se returna el total de la prden
                this.total = this.carts.reduce((total,item)=>{
                return total + item.cantidad* item.precio
                },0);
            }
        },
        //metodo para obtener todos los tipos de comida
        GetTypes(url){
            //se envia un get por axios
            axios
            .get(url)
            //respuesta de axios
            .then(({data:datos})=>{
                //se iguala el arreglo con la data que envia laravel
                this.types = datos.data
            })
        },
        //metodo que obtiene los productos por el id del tipo
        GetProducts(id){
            //se envia un post por axios con el id del tipo
            axios
            .post('/producto/searchType',{tipo:id})
            //respuesta de axios
            .then(({data:datos})=>{
                //se igauala el arreglo con la data que envia laravel
                this.products = datos.data
                //se muestra el listado de productos
                this.showProducts=true;
            })
            //en caso de error
            .catch(err=>{
                //se muestra por consola el error
                console.log(err.response.data);
            })
        }
    }
}
</script>
