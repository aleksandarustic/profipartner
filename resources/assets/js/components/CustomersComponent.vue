<template>
      <div class="container-fluid">

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title mb-0">Customers Table</h3>
            <div class="card-tools">
                <button class="btn btn-success" @click="add_customer_modal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
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
                            <span v-if="props.column.field == 'modify'">
                                <a href="#" @click="edit_customer_modal(props.row)"><i class="fas fa-user-cog"></i></a>
                                <a href="#" @click.prevent="delete_customer(props.row.id)"><i class="fas fa-user-slash text-danger"></i></a>
                            </span>
                            <span v-else>
                                {{props.formattedRow[props.column.field]}}
                            </span>
                        </template>

                    </vue-good-table>

                         <form @submit.prevent="!edit_mode ? add_customer() : edit_customer()">

                        <b-modal id="user_modal" v-model="modal_displayed" size="lg" :title="!edit_mode ? 'Add New Customer' : 'Update Customer\'s Info'">
                            <div class="form-group">
                                <input v-model="form.username" type="text" name="username" placeholder="Username" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('username') }">
                                <has-error :form="form" field="username"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email" placeholder="Email Address" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.provider" name="provider" type="text" id="provider" placeholder="Provider(Facebook | Google) Optional"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('provider') }">
                                <has-error :form="form" field="provider"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.provider_id" name="provider_id" type="text" id="provider_id" placeholder="Provider id Optional"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('provider_id') }">
                                <has-error :form="form" field="provider_id"></has-error>
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
  mounted() {
    console.log("Component mounted.");
  },
  created: function() {
    this.loadItems();
  },
  data: function() {
    return {
      modal_displayed: false,
      edit_mode: false,
      form: new Form({
        id: "",
        username: "",
        email: "",
        provider: '',
        provider_id: ''
      }),
      columns: [
        {
          label: "ID",
          field: "id",
        },
        {
          label: "Username",
          field: "username"
        },
        {
          label: "Email",
          field: "email"
        },
        {
          label: "Provider",
          field: "provider"
        },
        {
          label: "Registered At",
          field: "created_at"
        },
         {
          label: "Available points",
          field: "points"
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
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      if (
        this.serverParams.page != params.currentPage &&
        this.serverParams.perPage == params.currentPerPage
      ) {
        this.updateParams({ page: params.currentPage });
        this.loadItems();
      }
    },
    onPerPageChange(params) {
      this.updateParams({ perPage: params.currentPerPage });
      this.loadItems();
    },
    onSortChange(params) {
      this.updateParams({
        sort: {
          type: params.sortType,
          field: this.columns[params.columnIndex].field
        }
      });
      this.loadItems();
    },
    loadItems() {
      axios.post("/api/customers/load", this.serverParams).then(response => {
        this.totalRecords = response.data.totalRecords;
        this.rows = response.data.rows;
      });
    },
    add_customer_modal: function() {
      this.form.reset();
      this.edit_mode = false;
      this.modal_displayed = true;
    },
    edit_customer_modal: function(user) {
      this.form.reset();
      this.edit_mode = true;
      this.modal_displayed = true;
      this.form.fill(user);
    },
    edit_customer: function() {
      this.$Progress.start();
      this.form
        .put("/api/customers/" + this.form.id)
        .then(response => {
          this.loadItems();
          this.$Progress.finish();
          swal("Updated!", "Customer has been updated.", "success");
          this.modal_displayed = false;
        })
        .catch(exception => {
          this.$Progress.fail();
        });
    },
    add_customer: function() {
      this.$Progress.start();
      this.form
        .post("/api/customers")
        .then(request => {
          this.loadItems();
          this.$Progress.finish();
          this.modal_displayed = false;
          toast({
            type: "success",
            title: "Customer created succesfuly"
          });
        })
        .catch(exception => {
          this.$Progress.fail();
        });
    },
    delete_customer: function(customer_id) {
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
            .delete("/api/customers/" + customer_id)
            .then(response => {
              swal("Deleted!", "Customer has been deleted.", "success");
              this.loadItems();
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
