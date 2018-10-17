<template>
    <div class="container-fluid">
    <div class="row mt-3">
        <div class="col-12">
             <div class="card">
                    <div class="card-header">
                         <h3 class="card-title mb-0">Loyalty Rewards</h3>      
                        <div class="card-tools">
                          <button class="btn btn-success" @click="add_reward_modal">Add New Reward <i class="fas fa-plus-circle"></i></button>
                        </div>              
                    </div>

                    <div class="card-body  border-0">
                        <vue-good-table
                        :columns="table_data.columns"
                        :rows="table_data.rows"
                        styleClass="vgt-table bordered p-0 m-0"
                        :pagination-options="{
                          enabled: true,
                            perPage: 10,
                            position: 'bottom',
                            perPageDropdown: [5, 10, 20, 50],
                         }
                         "
                        >

                        <template slot="table-row" slot-scope="props">
                            <span  v-if="props.column.field == 'image'">
                                  <lightbox album="" :src="'/storage/reward_images/'+props.row.image">
                                    <b-img class="reward_thumbnails mx-auto d-block" width="150px" center thumbnail :src="'/storage/reward_images/'+props.row.image" fluid alt="Fluid image" />
                                 </lightbox>
                            </span>
                            <span v-else-if="props.column.field=='modify'">
                                <a href="#" @click.prevent="edit_reward_modal(props.row)"><i class="fas fa-pencil-alt"></i></a>
                                <a href="#" @click.prevent="delete_reward(props.row.id)"><i class="fas fa-trash-alt text-danger"></i></a>
                            </span>
                            <span v-else-if="props.column.field=='status'">
                                 {{props.formattedRow[props.column.field] == 0 ? 'No' : 'Yes'}}
                            </span>
                            <span v-else>
                                {{props.formattedRow[props.column.field]}}
                            </span>
                        </template>

                        </vue-good-table>

          <form @submit.prevent="!edit_mode ? add_reward() : edit_reward()">

                        <b-modal id="reward_modal" v-model="modal_displayed" size="lg" :title="!edit_mode ? 'Add New Reward' : 'Update Reward\'s Info'">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="Name" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <textarea v-model="form.description" name="description" id="description" placeholder="Short description for reward (Optional)"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                                <has-error :form="form" field="description"></has-error>
                            </div>

                            <div class="form-group">
                                  <b-form-file  v-model="form.image" name="image" id="image" accept="image/*" placeholder="Image" :state=" form.errors.has('image') ? false: null"></b-form-file>
                                  <has-error :form="form" field="image"></has-error>
                             </div>

                            <div class="form-group">
                                <input v-model="form.points" name="points" type="number" id="points" placeholder="0"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('points') }">
                                <has-error :form="form" field="points"></has-error>
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
    </div>
    </div>

</template>

<script>
export default {
  created() {
    this.$on("datachanged", this.loadTableData);
    this.$emit("datachanged");
  },
  mounted() {
    console.log("Component mounted.");
  },
  data: function() {
    return {
      modal_displayed: false,
      edit_mode: false,
      form: new Form({
        id: "",
        name: "",
        description: "",
        image: "",
        points: "",
      }),
      table_data: {
        columns: [
          {
            label: "ID",
            field: "id",
            type: "number"
          },
          {
            label: "Name",
            field: "name",
            type: "string"
          },
          {
            label: "Image",
            field: "image",
            sortable: false
          },
          {
            label: "Description",
            field: "description",
            type: "string"
          },
          {
            label: "Points",
            field: "points",
            type: "number"
          },
          {
            label: "Date created",
            field: "created_at"
          },
          {
            label: "Modify",
            field: "modify",
            sortable: false
          }
        ],
        rows: []
      }
    };
  },
  methods: {
    loadTableData() {
      this.$Progress.start();
      axios
        .get("/api/rewards")
        .then(response => {
          this.$Progress.finish();
          this.table_data.rows = response.data;
        })
        .catch(exception => {
          this.$Progress.fail();
          swal({
            title: "Error:" + exception,
            type: "error"
          });
        });
    },
    add_reward_modal: function() {
      this.form.reset();
      this.edit_mode = false;
      this.modal_displayed = true;
    },
    edit_reward_modal: function(reward) {
 
      this.form.reset();
      this.edit_mode = true;
      this.modal_displayed = true;
      this.form.id = reward.id;
      this.form.name = reward.name;
      this.form.description = reward.description;
      this.form.points = reward.points;
    },
    edit_reward: function() {
      this.$Progress.start();
      this.form
         .submit("post", "/api/rewards/"+this.form.id, {
          transformRequest: [
            function(data, headers) {
              return objectToForm(data);
            }
          ]
        })
        .then(response => {
          this.$emit("datachanged");
          this.$Progress.finish();
          swal("Updated!", "Reward has been updated.", "success");
          this.modal_displayed = false;
        })
        .catch(exception => {
          this.$Progress.fail();
        });
    },
    add_reward: function() {
      this.$Progress.start();

      this.form
        .submit("post", "/api/rewards", {
          transformRequest: [
            function(data, headers) {
              return objectToForm(data);
            }
          ]
        })
        .then(response => {
          this.$emit("datachanged");
          this.$Progress.finish();
          this.modal_displayed = false;
          toast({
            type: "success",
            title: "Reward created succesfuly"
          });
        })
        .catch(exception => {
          this.$Progress.fail();
        });

    },
    delete_reward: function(reward_id) {
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
            .delete("/api/rewards/" + reward_id)
            .then(response => {
              swal("Deleted!", "Reward has been deleted.", "success");
              this.$emit("datachanged");
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
