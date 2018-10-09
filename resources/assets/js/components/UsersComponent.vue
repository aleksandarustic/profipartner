<template>
      <div class="container-fluid">

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title mb-0">Users Table</h3>
            <div class="card-tools">
                <button class="btn btn-success" @click="add_user_modal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
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
                                <a href="#" @click="edit_user_modal(props.row)"><i class="fas fa-user-edit"></i></a>
                                <a href="#" @click.prevent="delete_user(props.row.id)"><i class="fas fa-user-minus"></i></a>
                            </span>
                            <span v-else>
                                {{props.formattedRow[props.column.field]}}
                            </span>
                        </template>

                    </vue-good-table>

                         <form @submit.prevent="!edit_mode ? add_user() : edit_user()">

                        <b-modal id="user_modal" v-model="modal_displayed" size="lg" :title="!edit_mode ? 'Add New' : 'Update User\'s Info'">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="Name" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email" placeholder="Email Address" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <textarea v-model="form.bio" name="bio" id="bio" placeholder="Short bio for user (Optional)"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password" id="password"
                                    placeholder="Password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>

                            <div class="form-group">

                              <b-form-select required multiple :select-size="3" name="roles" id="roles" v-model="form.selected_roles" :options="$parent.role_types" class="mb-3">
                            
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
        name: "",
        email: "",
        password: "",
        bio: "",
        photo: "",
        selected_roles:[]
      }),
      columns: [
        {
          label: "ID",
          field: "id",
        },
        {
          label: "Name",
          field: "name"
        },
        {
          label: "Email",
          field: "email"
        },
        {
          label: "Roles",
          field: "roles_name",
          sortable: false,
        },
        {
          label: "Registered At",
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
      axios.post("/api/users/load", this.serverParams).then(response => {
        this.totalRecords = response.data.totalRecords;
        this.rows = response.data.rows;
      });
    },
    add_user_modal: function() {
      this.form.reset();
      this.edit_mode = false;
      this.modal_displayed = true;
    },
    edit_user_modal: function(user) {
      this.form.reset();
      this.edit_mode = true;
      this.modal_displayed = true;
      this.form.fill(user);
    },
    edit_user: function() {
      this.$Progress.start();
      this.form
        .put("/api/users/" + this.form.id)
        .then(response => {
          this.loadItems();
          this.$Progress.finish();
          swal("Updated!", "User has been updated.", "success");
          this.modal_displayed = false;
        })
        .catch(exception => {
          this.$Progress.fail();
        });
    },
    add_user: function() {
      this.$Progress.start();
      this.form
        .post("/api/users")
        .then(request => {
          this.loadItems();
          this.$Progress.finish();
          this.modal_displayed = false;
          toast({
            type: "success",
            title: "User created succesfuly"
          });
        })
        .catch(exception => {
          this.$Progress.fail();
        });
    },
    delete_user: function(user_id) {
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
            .delete("/api/users/" + user_id)
            .then(response => {
              swal("Deleted!", "User has been deleted.", "success");
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
