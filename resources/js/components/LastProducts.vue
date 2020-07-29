<template>
    <div class="container">
        <button class="btn" data-target="#myModalRight" data-toggle="modal">
            <i class="fa fa-shopping-cart" style="font-size:30px"></i>
            <span class="badge badge-info text-white float-left">{{badge}}</span>
        </button>
        <div class="row">
            <div class="col-md-1 ml-5"></div>
            <div class="col-md-3 col-sm-1"  v-for="(producto, index) in productos" :key="index">
                <div class="card card-custom bg-white border-white border-0">
                    <div class="card-custom-img" style="background-image: url(/images/pano.png);">
                        <span class="badge badge-warning ml-2 mt-2">Nuevo</span>
                    </div>
                    <div class="card-custom-avatar">
                        <img class="img-fluid" :src="'/images/'+producto.image" />
                    </div>
                    <div class="card-body" style="overflow-y: auto">
                        <h4 class="card-title">{{producto.nombre}}</h4>
                        <p class="card-text">Precio: <span class="badge badge-success">${{producto.precio}}</span></p>
                        <p class="text-primary">Categoria: <span class="text-small text-dark">{{producto.tipo}}</span></p>
                    </div>
                    <div class="card-footer" style="background: inherit; border-color: inherit;">
                        <a href="#" @click="addCart(producto)" class="btn-sm btn btn-success">ordenar</a>
                    </div>
                </div>
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
        return {
            productos:[],
            carts:[],
            cartAdd:{
                id:'',
                nombre:'',
                precio:'',
                cantidad:1,
                image:"",
            },
            badge:'0',
            quantity:1,
            total:'0',
        }
    },
    mounted() {
        axios
        .get('/home/show')
        .then(({data:datos})=>{
            this.productos=datos.data
        });
        this.viewCart();
    },
    methods: {
        SaveOrder(){
            let data = {
            data:this.carts
            };
            axios
            .post('/order/store',{data})
            .then(resp=>{
                this.carts = [];
                this.total='0';
                this.badge='0';
                $('#cart-modal').modal('hide');
                localStorage.removeItem('carts');
                toast.fire({
                    icon: 'success',
                    title: 'Se orden a sido registrada ',
                });
            })
            .catch(err=>{
                console.log(err.response.data);
            })
        },
        viewCart(){
            if(localStorage.getItem('carts')){
                this.carts = JSON.parse(localStorage.getItem('carts'));
                this.badge = this.carts.length;
                this.total = this.carts.reduce((total,item)=>{
                return total + item.cantidad* item.precio
                },0);
            }
        },
        addCart(prod){
            var f =_.find(this.carts,['id',prod.id]);
            if(typeof f == 'object'){
                var index = _.indexOf(this.carts,f)
                this.carts[index].cantidad++
                toast.fire({
                    icon: 'success',
                    title: 'Su orden se ah actualizado',
                });
                this.storeCart();
                this.viewCart();
            }else{
                this.cartAdd.id = prod.id;
                this.cartAdd.nombre = prod.nombre;
                this.cartAdd.precio = prod.precio;
                this.cartAdd.cantidad = this.quantity;
                this.cartAdd.image = prod.image;
                if(this.carts.push(this.cartAdd)){
                    toast.fire({
                        icon: 'success',
                        title: 'Se agrego a la orden ',
                    });
                }
                this.cartAdd={};
                this.storeCart();
                this.viewCart();
            }
        },
        removeCart(cart){
            var f =_.find(this.carts,['id',cart.id]);
            if(typeof f == 'object'){
                var index = _.indexOf(this.carts,f)
                if(this.carts[index].cantidad <= 1){
                    this.carts.splice(cart,1);
                    this.storeCart();
                }else{
                    this.carts[index].cantidad--
                    this.storeCart();
                }
            }
        },
        storeCart(){
            let parsed = JSON.stringify(this.carts);
            localStorage.setItem('carts',parsed);
            this.viewCart();
        },
    },
}
</script>
