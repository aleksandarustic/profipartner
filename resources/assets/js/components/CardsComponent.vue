<template>
    <div class="container-fluid">
    <div class="row mt-3">
        <div class="col-12">
             <div class="card">
                    <div class="card-header">
                         <h3 class="card-title mb-0">Loyalty Cards</h3>                    
                    </div>

                    <div class="card-body  border-0">
                        <vue-good-table
                        :columns="table_data.columns"
                        :rows="table_data.rows"
                        styleClass="vgt-table bordered p-0 m-0"
                        :rowStyleClass="rowStyleClassFn"
                        :pagination-options="{
                          enabled: true,
                            perPage: 2,
                            position: 'bottom',
                            perPageDropdown: [2, 3, 5, 10, 25],
                         }
                         "
                        >

                        <template slot="table-row" slot-scope="props">
                            <span  v-if="props.column.field == 'image'">
                                  <lightbox album="" :src="'/storage/card_images/'+props.row.image">
                                    <b-img class="card_thumbnails mx-auto d-block" width="150px" center thumbnail :src="'/storage/card_images/'+props.row.image" fluid alt="Fluid image" />
                                 </lightbox>
                            </span>
                            <span v-else-if="props.column.field=='modify'">
                                <a href="#" v-if="props.row.status == 0" @click.prevent="add_card_points(props.row.id,props.row.points)"><i class="fas fa-pencil-alt"></i></a>
                                <a href="#" @click.prevent="delete_card(props.row.id)"><i class="fas fa-trash-alt text-danger"></i></a>
                            </span>
                            <span v-else-if="props.column.field=='status'">
                                 {{props.formattedRow[props.column.field] == 0 ? 'No' : 'Yes'}}
                            </span>
                            <span v-else>
                                {{props.formattedRow[props.column.field]}}
                            </span>
                        </template>

                        </vue-good-table>
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
      table_data: {
        columns: [
          {
            label: "ID",
            field: "id",
            type: "number"
          },
          {
            label: "Customer",
            field: "customer.username",
            type: "string"
          },
          {
            label: "Receipt",
            field: "image",
            sortable: false
          },
          {
            label: "Note",
            field: "note",
            type: "string"
          },
          {
            label: "Points Gained",
            field: "points",
            type: "number"

          },
          {
            label: "Validated",
            field: "status",
            type: 'number'
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
        .get("/api/cards")
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
      console.log("loaded");
    },
     rowStyleClassFn(row) {
       return row.status == 1 ? 'table-active' : '';
     },
    add_card_points: function(card_id,current_points) {
      swal({
        title: "Add points to customer card",
        input: "text",
        inputValue: current_points,
        inputAttributes: {
          autocapitalize: "on"
        },
        showCancelButton: true,
        confirmButtonText: "Add Points",
        showLoaderOnConfirm: true,
        preConfirm: points => {
          if (points) {
            this.$Progress.start();
            axios
              .put("/api/cards/" + card_id, { points: points })
              .then(response => {
                swal("Success!", "Points has been updated.", "success");
                this.$emit("datachanged");
                this.$Progress.finish();
              })
              .catch(exception => {
                this.$Progress.fail();

                 swal({
                  title: "Error:" + exception.response.data.message,
                  type: "error"
                 });
              });
          }
          else{
              swal.showValidationMessage('Points can not be empty');
          }
        },
        allowOutsideClick: () => !swal.isLoading()
      });
    },
    delete_card: function(card_id) {
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
            .delete("/api/cards/" + card_id)
            .then(response => {
              swal("Deleted!", "Card has been deleted.", "success");
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
