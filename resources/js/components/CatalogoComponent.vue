<template>
    <div class="container mt-5">
        <div>
            <div class="row mt-2">
                <div class="col-md-11">
                    <h4 class="lead mb-3">Aqui encontraras la mejor comida</h4>
                </div>
                <div class="col-md-1">
                    <button class="btn" data-target="#myModalRight" data-toggle="modal">
                    <i class="fa fa-shopping-cart" style="font-size:30px"></i>
                    <span style="position:absolute;top:-4px;left:10px" class="badge badge-info text-white">{{badge}}</span>
                    </button>
                </div>
            </div>
            <div v-if="showDetail===false" class="row mb-4">
                <div class="col-md-6">
                    <input @keyup="Search()" placeholder="Escribe para buscar un producto" v-model="search" type="text" class="form-control">
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
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showSearch===true" class="row">
            <p class="lead" v-if="!productSearch.length">No hay productos para mostrar</p>
            <div class="col-3"  v-for="(prod, index) in productSearch" :key="index">
                <div class="card card-custom bg-white border-white border-0">
                    <div class="card-custom-img" style="background-image: url(https://image.freepik.com/foto-gratis/pano-cocina-mesa-madera_74190-7693.jpg);">
                    </div>
                    <div class="card-custom-avatar">
                        <img class="img-fluid" :src="'/images/'+prod.image" />
                    </div>
                    <div class="card-body" style="overflow-y: auto">
                        <h4 class="card-title">{{prod.nombre}}</h4>
                        <p class="card-text">Precio: ${{prod.precio}}</p>
                        <p class="text-primary">Categorias</p>
                        <p class="card-text text-small">{{prod.tipo}}</p>
                    </div>
                    <div class="card-footer" style="background: inherit; border-color: inherit;">
                        <button role="button" @click="addCart(prod)" class="btn btn-sm btn-success text-white">Ordenar</button>
                        <a href="#" class="btn-sm btn btn-primary">Ver Mas</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="showSearch===false && showDetail===false" >
            <div class="col-3"  v-for="(producto, index) in productos" :key="index">
                <div class="card card-custom bg-white border-white border-0">
                    <div class="card-custom-img" style="background-image: url(/images/pano.png);"></div>
                    <div class="card-custom-avatar">
                        <img class="img-fluid" :src="'/images/'+producto.image" />
                    </div>
                    <div class="card-body" style="overflow-y: auto">
                        <h4 class="card-title">{{producto.nombre}}</h4>
                        <p class="card-text">Precio: <span class="badge badge-success">${{producto.precio}}</span></p>
                        <p class="text-primary">Categorias: <span class="text-dark text-small">{{producto.tipo}}</span></p>
                    </div>
                    <div class="card-footer" style="background: inherit; border-color: inherit;">
                        <a role="button" @click="addCart(producto)" class="btn btn-sm btn-outline-success">Ordenar</a>
                        <a href="#" class="btn-sm btn btn-outline-primary" @click="Detail(producto.id)">Ver Mas</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5" v-if="showSearch===false && showDetail===false">
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
        <div id="myModalRight" class="modal fade modal-right" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tu carrito</h5>
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
     return{
        //varible de arreglo que obtiene los datos de paginacion
        pagination:[],
        //variable de arreglo que obtiene los datos de los productos
        productos:[],
        //variable de arreglo que obtiene los productos del carrit
        carts:[],
        //varible temporal que obtiene el  producto del carrito
        cartAdd:{
            id:'',
            nombre:'',
            precio:'',
            cantidad:1,
            image:"",
        },
        //variable que obtiene la cantidad de productos del carrito
        badge:'0',
        //variable que inicializa la cantidad de productos del carrito
        quantity:1,
        //variable que obtiene el total de la orden
        total:'0',
        //varible que obtiene los datos de busqueda
        search:'',
        //muestra y oculta la seccion para buscar
        showSearch:false,
        //muestra y oculta la seccion de  detalle
        showDetail:false,
        //muestra y oculta la seccion de rating
        getRating:false,
        //obtiene los datos del producto que se busco
        productSearch:[],
        //obtiene los datos del detalle del producto
        detail:[],
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
 methods: {
    //envia un post a laravel para agregar el rating
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
     },
    //metodo para mostrar los productos del carrito
    viewCart(){
        //se comprueba que exitan productos en el carrito
        if(localStorage.getItem('carts')){
            //se iguala la variable al arreglo de productos del carrito
            this.carts = JSON.parse(localStorage.getItem('carts'));
            //se inicializa la cantidad de productos del carrito
            this.badge = this.carts.length;
            //se establece el total de la orden
            this.total = this.carts.reduce((total,item)=>{
                return total + item.cantidad* item.precio
            },0);
        }
    },
    //metodo para agregar al carrito
    addCart(prod){
        //variable que se usa para buscar un producto en el carrito
        var f =_.find(this.carts,['id',prod.id]);
        //se comprueba si hay un producto con el id de la variable
        if(typeof f == 'object'){
            //variable que guarda la posicion del producto repetido
            var index = _.indexOf(this.carts,f)
            //se incrementa la cantida del producto repetido
            this.carts[index].cantidad++
            //se muestra una alerta de exito
            toast.fire({
                icon: 'success',
                title: 'Su orden se ah actualizado',
            });
            //se ejecuta el metodo para guardar el producto en el carrito
            this.storeCart();
            //se ejecuta el metodo para ver los carritos del producto
            this.viewCart();
        }
        //si el producto no existe en el carrito
        else{
            //se iguala a la variable el id del producto
            this.cartAdd.id = prod.id;
            //se iguala a la variable el nombre del producto
            this.cartAdd.nombre = prod.nombre;
            //se iguala a la variable el precio del producto
            this.cartAdd.precio = prod.precio;
            //se iguala a la variable la cantidad del producto
            this.cartAdd.cantidad = this.quantity;
            //se iguala a la variable la imagen del producto
            this.cartAdd.image = prod.image;
            //se agrega producto al arreglo del carrito
            if(this.carts.push(this.cartAdd)){
                //se muestra una alerta de exito
                toast.fire({
                    icon: 'success',
                    title: 'Se agrego a la orden ',
                });
            }
            //se vacia la variable temporal de los productos
            this.cartAdd={};
            //se ejecuta el metodo para guardar el producto
            this.storeCart();
            //se ejecuta el metodo para ver los productos del carrito
            this.viewCart();
        }
    },
    //metodo para eliminar el producto del carrito
    removeCart(cart){
        //variable que busca si el producto ya existe en el carrito
        var f =_.find(this.carts,['id',cart.id]);
        //se verifica si existe el producto
        if(typeof f == 'object'){
            //se guarda la pocision del producto repetido
            var index = _.indexOf(this.carts,f)
            //se verifica que la cantidad sea menor o igual a uno
            if(this.carts[index].cantidad <= 1){
                //se elimina el producto del carrito
                this.carts.splice(cart,1);
                //se ejecuta el metodo de guardar en el carrito
                this.storeCart();
            }
            //si la cantidad del producto es mayor a uno
            else{
                //se reduce la cantidad del producto
                this.carts[index].cantidad--
                //se ejecuta el metodo para guardar el carrito
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
    //metodo que muestra todos los productos en la base de datos
    GetResult(url){
        //se recibe por axios
        axios
        .get('/producto/show')
        //respuesta que envia laravel
        .then(({data:datos})=>{
            //la variable se iguala al arreglo que envia laravel
            this.productos = datos.data
            //se usa para la paginacion
            this.pagination={
                //pagina actual
                current_page:datos.meta.current_page,
                //pagina anterior de la paginacion
                last_page:datos.meta.last_page,
                //pagina de destino de la paginacion
                from_page:datos.meta.from,
                //numero de resultados de la paginacion
                to_page:datos.meta.to,
                //total de paginas en la paginacion
                total_pages:datos.meta.total,
                path_page:datos.meta.path+"?page=",
                //primera pagina de la paginacion
                first_link:datos.links.first,
                //ultima pagina de la paginacion
                last_link:datos.links.last,
                //pagina anterior de la paginacion
                prev_link:datos.links.prev,
                //pagina siguiente de la paginacion
                next_link:datos.links.next
            };
        })
        //en caso de error
        .catch(err=>{
            //se muestra por pantalla el mensaje de error
            console.log(err.response.data)
        })
    },
    //metodo para guardar la orden en la base de datos
    SaveOrder(){
        //variable que obtiene los productos del carrito
        let data = {
           data:this.carts
        };
        //se envia un post por axios a laravel con los datos del carrito
        axios
        .post('/order/store',{data})
        //respuesta de laravel
        .then(resp=>{
            //se vacia el arreglo de productos del carrito
            this.carts = [];
            //se vacia el total de la compra
            this.total='0';
            //se vacia la cantidad de productos del carrito
            this.badge='0';
            //se cierra el modal del carrito
            $('#cart-modal').modal('hide');
            //se vacia el local storage
            localStorage.removeItem('carts');
            //se muestra una alerta
            toast.fire({
                icon: 'success',
                title: 'Se orden a sido registrada ',
            });
       })
       //en caso de error
       .catch(err=>{
           //se muestra el mensaje de error en la consola
           console.log(err.response.data);
       })
    },
    //metodo para buscar productos en el catalogo
    Search(){
        //se envia un post por axios a laravel con el nombre del producto
        axios
        .post('/producto/search',{nombre:this.search})
        //respuesta de laravel
        .then(({data:prod})=>{
            //la variable recibe el arreglo de productos con el nombre enviado
            this.productSearch = prod.data
            //se muestra la seccion de buscar
            this.showSearch = true
        })
    },
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
    //metodo que obtiene el rating del producto seleccionado
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
 },
 //metodos que se ejuctan al inicializar el componente
 mounted() {
     //se ejecuta el metodo para obtener todos los productos
     this.GetResult('/producto/show');
     //se ejecuta el metodo para ver los productos del carrito
     this.viewCart()
 },
}
</script>
