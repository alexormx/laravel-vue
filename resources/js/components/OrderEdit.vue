<template>
    <div>
        <div class="row mt-5"  v-if="$gate.isAdmin()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Order Table</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i> </button>
                        </div>
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Sale Method</th>
                                <th>Pay Method</th>
                                <th>Ship Method</th>
                                <th>Tracking</th>
                                <th>Ship Cost</th>
                                <th>Product Cost</th>
                                <th>Total</th>
                                <th>Modify</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <div class="form-group row">
                                    <td>
                                        <input v-model="form.id" type="text" name="id"
                                               placeholder="ID"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('id') }">
                                        <has-error :form="form" field="id"></has-error>
                                    </td>
                                    <td>
                                        <input v-model="form.order_id" type="text" name="order_id"
                                               placeholder="Order"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('order_id') }">
                                        <has-error :form="form" field="order_id"></has-error>
                                    </td>
                                    <td>
                                        <input v-model="form.user_id" type="text" name="user_id"
                                               placeholder="User"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('user_id') }">
                                        <has-error :form="form" field="user_id"></has-error>
                                    </td>
                                    <td>
                                        <input v-model="form.saledt" type="date" name="saledt"
                                               placeholder="Sale Date"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('saledt') }">
                                        <has-error :form="form" field="saledt"></has-error>
                                    </td>
                                    <td>
                                        <input v-model="form.salemethod" type="text" name="salemethod"
                                               placeholder="Sale Method"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('salemethod') }">
                                        <has-error :form="form" field="salemethod"></has-error>
                                    </td>
                                    <td>
                                        <input v-model="form.paymethod" type="text" name="paymethod"
                                               placeholder="Paymethod"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('paymethod') }">
                                        <has-error :form="form" field="paymethod"></has-error>
                                    </td>
                                    <td>
                                        <input v-model="form.shipmethod" type="text" name="shipmethod"
                                               placeholder="Shipmethod"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('shipmethod') }">
                                        <has-error :form="form" field="shipmethod"></has-error>
                                    </td>
                                    <td>
                                        <input v-model="form.tracking" type="text" name="tracking"
                                               placeholder="Tracking"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('tracking') }">
                                        <has-error :form="form" field="tracking"></has-error>
                                    </td>
                                    <td>
                                        <input v-model="form.shipcost" type="number" step="0.01" name="shipcost"
                                               placeholder="Shipping Cost"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('shipcost') }">
                                        <has-error :form="form" field="shipcost"></has-error>
                                    </td>
                                    <td>
                                        <input v-model="form.prodcost" type="number" step="0.01" name="prodcost"
                                               placeholder="Product Cost"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('prodcost') }">
                                        <has-error :form="form" field="prodcost"></has-error>
                                    </td>
                                    <td>
                                        <input v-model="form.totalcost" type="number" step="0.01" name="totalcost"
                                               placeholder="Total Cost"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('totalcost') }">
                                        <has-error :form="form" field="totalcost"></has-error>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary" @click.prevent="createItem()" id="name" name="name">
                                            <span class="fa fa-plus"></span> ADD </button>
                                    </td>
                                </div>
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
                orders: {},
                form: new Form({
                    id: '',
                    order_id: '',
                    user_id: '',
                    saledt: '',
                    salemethod: '',
                    paymethod: '',
                    shipmethod: '',
                    tracking: '',
                    shipcost: '',
                    prodcost: '',
                    totalcost: ''
                })
            }
        },

        methods: {
            getResults(page = 1) {
                axios.get('api/order?page=' + page)
                    .then(response => {
                        this.orders = response.data;
                    });
            },
            updateOrder(id){
                this.$Progress.start();
                //console.log('Editing');
                this.form.put('api/order/'+this.form.id)
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
            editModal(order){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(order);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteOrder(id){
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
                        this.form.delete('api/order/'+id).then(()=>{
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
            loadOrders(){
                if(this.$gate.isAdmin()){
                    axios.get("api/order")
                        .then(({data})=>{this.orders = data;})
                }
            },
            createOrder(){
                this.$Progress.start();
                this.form.post('api/order')
                    .then(()=>{
                        Fire.$emit('AfterCreate');
                        $('#addNew').modal('hide')
                        Toast.fire({
                            type: 'success',
                            title: 'Order Created in successfully'
                        })
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        Swal.fire("Failed!", "There was something wronge.", "warning");
                    })
            }
        },
        created() {
            this.loadOrders();
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findOrders?q=' + query)
                    .then((data) => {
                        this.orders = data.data
                    })
                    .catch(() => {

                    })
            })
            Fire.$on('AfterCreate', ()=>{
                this.loadOrders();
            });
            // setInterval(() => {this.loadUsers()},3000);
        }
    }
</script>