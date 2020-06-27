<template>
    <div>
        <div class="row mt-5"  v-if="$gate.isAdmin()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Product Table</h3>

                    </div>

                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Picture</th>
                                <th>ID</th>
                                <th>Code</th>
                                <th>SKU</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Published</th>
                                <th>Inventory</th>
                                <th>Suggested Price</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="product in products" :key="product.id">
                                <td>
                                    <img :src="'./img/prod/' + product.product_id +'.jpg' || './img/prod/' + product.product_id +'.png'" :alt="product.product_id" height="50" width="50">
                                    <img :src="'./img/prod/' + product.product_id +'1.jpg' || './img/prod/\' + product.product_id +\'1.png'" :alt="product.product_id" height="50" width="50">
                                </td>

                                <td>{{product.id}}</td>
                                <td>{{product.product_id}}</td>
                                <td>{{product.sku}}</td>
                                <td>{{product.name}}</td>
                                <td>{{product.brand}}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input class="custom-control-input" :id="'customSwitch' + product.id" type="checkbox">
                                        <label class="custom-control-label" :for="'customSwitch' + product.id"></label>
                                    </div>
                                </td>
                                <td>{{product.inventory}}</td>
                                <td>$ {{product.price}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                editmode: false,
                products: {},
                form: new Form({
                    id: '',
                    product_id: '',
                    sku: '',
                    name: '',
                    brand: '',
                    price: '',
                })
            }
        },

        methods: {
            loadProducts(){
                if(this.$gate.isAdmin()){
                    axios.get("api/publish")
                        .then(({data})=>{this.products = data;})
                }
            },
        },
        created() {
            this.loadProducts();
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findProduct?q=' + query)
                    .then((data) => {
                        this.products = data.data
                    })
                    .catch(() => {

                    })
            })
            Fire.$on('AfterCreate', ()=>{
                this.loadProducts();
            });
            // setInterval(() => {this.loadUsers()},3000);
        }
    }
</script>