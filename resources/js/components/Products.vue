<template>
    <div>
        <div class="row mt-5"  v-if="$gate.isAdmin()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Product Table</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i> </button>
                            <button class="btn btn-warning" @click="actualizar">Update <i class="fas fa-sync fa-fw"></i> </button>
                        </div>
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code</th>
                                <th>SKU</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Purchase</th>
                                <th>Saled</th>
                                <th>Inventory</th>
                                <th>Suggested Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="product in products.data" :key="product.id">
                                <td>{{product.id}}</td>
                                <td>{{product.product_id}}</td>
                                <td>{{product.sku}}</td>
                                <td>{{product.name}}</td>
                                <td>{{product.brand}}</td>
                                <td>{{product.purches.totals}}</td>
                                <td>{{product.sales.totals}}</td>
                                <td>{{product.purches.totals - product.sales.totals}}</td>
                                <td>$ {{product.price}}</td>
                                <td>
                                    <a href="#" @click="editModal(product)">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    /
                                    <a href="#" @click="deleteProduct(product.id)">
                                        <i class="fa fa-trash red"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">

                        <pagination :data="products" :limit = 2
                                    @pagination-change-page="getResults">
                            <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Product's Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateProduct() : createProduct()">
                        <div class="modal-body">

                            <div class="form-group">
                                <input v-model="form.id" type="text" name="id"
                                       placeholder="ID"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('id') }">
                                <has-error :form="form" field="id"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.product_id" type="text" name="product_id"
                                       placeholder="Code"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('product_id') }">
                                <has-error :form="form" field="product_id"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.sku" type="text" name="sku"
                                       placeholder="SKU"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('sku') }">
                                <has-error :form="form" field="sku"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name"
                                       placeholder="Name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.brand" type="text" name="brand"
                                       placeholder="Brand"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('brand') }">
                                <has-error :form="form" field="brand"></has-error>
                            </div>


                            <div class="form-group">
                                <input v-model="form.price" type="number" name="price"
                                       placeholder="Price"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('price') }">
                                <has-error :form="form" field="price"></has-error>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>

                </div>
            </div>
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
            getResults(page = 1) {
                axios.get('api/product?page=' + page)
                    .then(response => {
                        this.products = response.data;
                    });
            },
            updateProduct(id){
                this.$Progress.start();
                //console.log('Editing');
                this.form.put('api/product/'+this.form.id)
                    .then(() =>{
                        $('#addNew').modal('hide');
                        Swal.fire(
                            'Updated!',
                            'Information has been updated.',
                            'success'
                        )
                        this.$Progress.finish();
                        Fire.$emit('AfterCreate');
                    })
                    .catch(()=>{
                        this.$Progress.fail();
                    });
            },
            editModal(product){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(product);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteProduct(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    // Send request to the server
                    if (result.value) {
                        this.form.delete('api/product/'+id).then(()=>{
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            Fire.$emit('AfterCreate');
                        }).catch(()=> {
                            Swal.fire("Failed!", "There was something wronge.", "warning");
                        });
                    }
                })
            },
            loadProducts(){
                if(this.$gate.isAdmin()){
                    axios.get("api/product")
                        .then(({data})=>{this.products = data;})
                }
            },
            createProduct(){
                this.$Progress.start();
                this.form.post('api/product')
                    .then(()=>{
                        Fire.$emit('AfterCreate');
                        $('#addNew').modal('hide')
                        Toast.fire({
                            type: 'success',
                            title: 'Product Created in successfully'
                        })
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        Swal.fire("Failed!", "There was something wronge.", "warning");
                    })
            },
            actualizar(){
                axios.get("api/update")
            }
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