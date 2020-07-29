<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Email</th>
                            <th scope="col">Direccion</th>
                            <th scope="col">Telefono</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Fecha de creacion</th>
                            <th scope="col">Fecha de actualizacion</th>
                        </tr>
                    </thead>
                    <tbody v-for="(user, index) in users" :key="index">
                        <tr>
                            <td>{{user.name}}</td>
                            <td>{{user.email}}</td>
                            <td>{{user.direccion}}</td>
                            <td>{{user.telefono}}</td>
                            <td>{{user.role}}</td>
                            <td>{{user.created_at}}</td>
                            <td>{{user.updated_at}}</td>
                        </tr>
                    </tbody>
                </table>
                <nav aria-label="Page navigation example" class="d-flex justify-content-center text-primary">
                    <ul class="pagination">
                        <li v-if="prevpage" class="page-item"><a class="page-link" @click="GetResult(prevpage)">Previous</a></li>
                        <li class="page-item"><a class="page-link" v-text="current_page"></a></li>
                        <li v-if="nextpage" class="page-item"><a class="page-link" @click="GetResult(nextpage)">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            users: [],
            nextpage:null,
            prevpage:null,
            current_page: 0
        };
    },
    mounted() {
        this.GetResult('/user/show');

    },
    methods:{
        GetResult(url){
            axios
            .get(url)
            .then(({data:pagination})=>{
                this.users = pagination.data
                this.nextpage = pagination.links.next
                this.prevpage = pagination.links.prev
                this.current_page = pagination.meta.current_page
            })
            .catch(err=>{
            console.log(err.response.data);
            })
        },
    }
};
</script>
