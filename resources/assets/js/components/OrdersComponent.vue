<template>
      <div class="container-fluid">

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title mb-0">Loyalty Orders</h3>
            <div class="card-tools">
                <button class="btn btn-success" @click="add_order_modal">Add New <i class="fas fa-cart-plus"></i></button>
            </div>
        </div>
        <div class="card-body">
             <vue-good-table mode="remote" @on-page-change="onPageChange" @on-sort-change="onSortChange"
                        @on-per-page-change="onPerPageChange" :totalRows="totalRecords" :pagination-options="{
                                enabled: true,
                                perPageDropdown: [5,10,20,50],
                                perPage:10,
                            }"
                        :rows="rows" :columns="columns">

                        <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field == 'customer_id'">
                                {{props.row.customer.username}}
                            </span>
                            <span v-else-if="props.column.field == 'reward_id'">
                                {{props.row.reward.name}}
                            </span>
                            <span v-else-if="props.column.field == 'modify'">
                                <a href="#" @click="edit_order_modal(props.row)"><i class="fas fa-edit"></i></a>
                                <!-- <a href="#" @click.prevent="delete_order(props.row.id)"><i class="fas fa-trash-alt text-danger"></i></a> -->
                            </span>
                            <span v-else>
                                {{props.formattedRow[props.column.field]}}
                            </span>
                        </template>

                    </vue-good-table>

                         <form @submit.prevent="!edit_mode ? add_order() : edit_order()">

                        <b-modal id="order_modal" v-model="modal_displayed" size="lg" :title="!edit_mode ? 'Add New Order' : 'Update Order\'s'">
                            <div class="form-group">
                               <b-form-select required name="customer_id" id="customer_id" v-model="form.customer_id" :options="customer_options" class="mb-3" :state=" form.errors.has('customer_id') ? false: null">
                                   <option :value="null" disabled>-- Please select a customer  --</option>
                               </b-form-select>
                            </div>
                            <div class="form-group">
                                 <b-form-select required name="reward_id" id="reward_id" v-model="form.reward_id" :options="reward_options" class="mb-3" :state=" form.errors.has('reward_id') ? false: null">
                                   <option :value="null" disabled>-- Please select a reward  --</option>
                                </b-form-select>
                            </div>

                            <div slot="modal-footer">
                                <button type="button" class="btn btn-danger" @click="modal_displayed = false">Close</button>
                                <button v-show="edit_mode" type="submit" class="btn btn-success">Update</button>
                                <button v-show="!edit_mode" type="submit" class="btn btn-primary">Create</button>
                            </div>


                        </b-modal>
                 </form>


            
        </div>
        </div>

           
    </div>

</template>

<script>
export default {

  created: function() {
    this.$on("datachanged", this.loadTableData);
    this.$emit("datachanged");
    this.getRewardOptions();
    this.getCustomerOptions();
  },
  data: function() {
    return {
      modal_displayed: false,
      edit_mode: false,
      reward_options:[],
      customer_options:[],
      form: new Form({
        id: "",
        customer_id: null,
        reward_id: null,
        points: '',
      }),
      columns: [
        {
          label: "ID",
          field: "id",
        },
        {
          label: "Customer",
          field: "customer_id"
        },
        {
          label: "Reward",
          field: "reward_id"
        },
         {
          label: "Points spent",
          field: "points"
        },
        {
          label: "Ordered At",
          field: "created_at"
        },
    
        {
          label: "Modify",
          field: "modify",
          sortable: false,
          html: true
        }
      ],
      rows: [],
      totalRecords: 0,
      serverParams: {
        sort: {
          field: "created_at",
          type: "desc"
        },
        page: 1,
        perPage: 10
      }
    };
  },
  methods: {

    getRewardOptions(){
        axios.get('/api/rewards/options').then(result => {
            this.reward_options = result.data;
        });
    },
    getCustomerOptions(){
        axios.get('/api/customers/options').then(result => {
            this.customer_options = result.data;
        });
    },
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      if (
        this.serverParams.page != params.currentPage &&
        this.serverParams.perPage == params.currentPerPage
      ) {
        this.updateParams({ page: params.currentPage });
        this.$emit("datachanged")
      }
    },
    onPerPageChange(params) {
      this.updateParams({ perPage: params.currentPerPage });
      this.$emit("datachanged")
    },
    onSortChange(params) {

      this.updateParams({
        sort: {
          type: params.sortType,
          field: this.columns[params.columnIndex].field
        }
      });
      this.$emit("datachanged")
    },
    loadTableData() {
      axios.post("/api/orders/load", this.serverParams).then(response => {
        this.totalRecords = response.data.totalRecords;
        this.rows = response.data.rows;
      });
    },
    add_order_modal: function() {
      this.form.reset();
      this.edit_mode = false;
      this.modal_displayed = true;
    },
    edit_order_modal: function(order) {
      this.form.reset();
      this.edit_mode = true;
      this.modal_displayed = true;
      this.form.fill(order);
    },
    edit_order: function() {
      this.$Progress.start();
      this.form
        .put("/api/orders/" + this.form.id)
        .then(response => {
          this.$emit("datachanged")
          this.$Progress.finish();
          swal("Updated!", "Order has been updated.", "success");
          this.modal_displayed = false;
        })
        .catch(exception => {
          this.$Progress.fail();
        });
    },
    add_order: function() {
      this.$Progress.start();
      this.form
        .post("/api/orders")
        .then(request => {
          this.$emit("datachanged")
          this.$Progress.finish();
          this.modal_displayed = false;
          toast({
            type: "success",
            title: "Order created succesfuly"
          });
        })
        .catch(exception => {
          this.$Progress.fail();
             swal({
                title: "Error: " + exception.response.data.message,
                type: "error"
              });
        });
    },
    delete_order: function(order_id) {
      swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.value) {
          this.$Progress.start();
          axios
            .delete("/api/orders/" + order_id)
            .then(response => {
              swal("Deleted!", "Order has been deleted.", "success");
              this.$emit("datachanged")
              this.$Progress.finish();
            })
            .catch(exception => {
              this.$Progress.fail();
              swal({
                title: "Error:" + exception,
                type: "error"
              });
            });
        }
      });
    }
  }
};
</script>
