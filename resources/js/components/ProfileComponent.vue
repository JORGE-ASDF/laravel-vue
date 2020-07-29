<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <label class=" col-form-label col-md-3">Nombre:</label>
                                    <div class="col-md-9">
                                        <input id="name" type="text" :value="user.name" class="form-control" >
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <label class=" col-form-label col-md-3">Direccion:</label>
                                    <div class="col-md-9">
                                        <input id="direccion" type="text" class="form-control" :value="user.direccion">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <label class=" col-form-label col-md-3">Telefono:</label>
                                    <div class="col-md-9">
                                        <input id="telefono" type="text" class="form-control" :value="user.telefono">
                                    </div>
                                </div>
                                 <div class="row mt-3">
                                    <label class=" col-form-label col-md-3">Avatar:</label>
                                    <div class="col-md-9">
                                        <input v-on:change="onImageChange" type="file" class="form-control-file">
                                    </div>
                                </div>
                                <button @click="EditUser" class="mt-4 offset-2 col-md-3 btn btn-primary btn-sm">Guardar</button>
                            </div>
                            <div class="col-md-4">
                                <img id="img" :src="'/images/'+user.avatar" class=" rounded-circle" width="200" height="200" alt="">
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
            //variable que obtiene el arreglo con los usuarios
            user:[],
            //variable que obtiene el nombre del input de usuario
            name:"",
            //variable que obtiene el telefono del input de usuario
            telefono:"",
            //variable que obtiene la direccion del input de usuario
            direccion:"",
            //variable que obtiene el avatar del in input de usuario
            avatar:""
        }
    },
    mounted() {
        //metodo que obtiene el perfil del usuario loggueado
        axios
        //se obtiene por get desde axios
        .get('/user/profile')
        //respuesta de axios
        .then(({data:datos})=>{
            //igualamos la variable a los datos que envia laravel
            this.user = datos.data;
        })
        //en caso de error
        .catch(err=>{
            //se muestra el error por consola
            console.log(err.response.data);
        })
    },
    methods: {
        //metodo para mostrar la imagen que se selecciono
        onImageChange(e) {
            //variable de instancia del fileReader
            let reader = new FileReader();
            //lee la data de la url de la imagen
            reader.readAsDataURL(e.target.files[0]);
            //cuando la imagen se carga
            reader.onload = (e)=> {
                //se iguala la variable con el resultado
                this.avatar = e.target.result;
                //me cambia el src para mostrar la imagen
                document.getElementById('img').src = reader.result;
            }
        },
        //metodo para editar la imformacion del usuario
        EditUser(){
            //se iguala la variable con el valor que tiene el input name
            this.name = document.getElementById('name').value;
            //se iguala la variable con el valor que tiene el input direccion
            this.direccion = document.getElementById('direccion').value;
            //se iguala la variable con el valor que tiene el input de telefono
            this.telefono = document.getElementById('telefono').value;
            //se verifica si se ah sellecionado una imagen
            if(this.avatar!=""){
                //se verifica que los datos no esten vacios
                if(this.name!="" && this.direccion!="" && this.telefono!=""){
                    //se envia un post por axios
                    axios
                    .post('user/update',
                    //datos que lleva el post
                    {name:this.name,
                    direccion:this.direccion,
                    telefono:this.telefono,
                    avatar:this.avatar})
                    //respuesta de axios
                    .then(resp=>{
                        //se verifica la respuesta que envio laravel
                        if(resp.data==="success"){
                            //si es correcto se muestra una alerta
                            toast.fire({
                                title:'Su informacion se actualizo',
                                timer: 2000,
                                icon:'success',
                            });
                            //se redirecciona a la vista home
                            setTimeout(function(){
                                window.location.href = '/home';
                            },2000);
                        }
                    });
                }
                //si hay datos vacios
                else{
                    //se muestra una alerts
                    toast.fire({
                        title:'Datos vacios',
                        icon:'warning',
                    })
                }
            }
            //si no se ahsellecionado una imagem
            else{
                // se muestra una alerta de advertencia
                swal.fire({
                    icon: 'warning',
                    title:'Advertencia!! No seleccionaste ninguna foto',
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonText:'Aceptar',
                    confirmButtonColor:'#3085b6',
                    cancelButtonColor:'#d33',
                    cancelButtonText:'Cancelar',
                    text: 'Se guardara el avatar por defecto',
                })
                //respuesta del usuario
                .then((result)=>{
                    //si el usuario acepta la advertencia
                    if(result.value){
                        //se envia un post con axios
                        axios
                        .post('user/update',
                        //datos que envia axios
                        {name:this.name,
                        direccion:this.direccion,
                        telefono:this.telefono})
                        //respuesta que envia laravel
                        .then(resp=>{
                            //si verifica la respuesta
                            if(resp.data==="success"){
                                //si es correcta se muestra una alerta
                                toast.fire({
                                    title:'Su informacion se actualizo',
                                    icon:'success',
                                });
                                //se redirige a la vista home
                                setTimeout(function(){
                                window.location.href = '/home';
                                },2000);
                            }
                        });
                    }
                })
            }
        }
    },
}
</script>
