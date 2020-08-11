<template>
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
</template>

<script>
export default {
    data(){
        return{
            carts:[],
        }
    },
    mounted(){
        this.viewCart();
    },
    methods:{
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
    },


}
</script>

<style>

</style>
