<template>
    <div class="container-fluid">
    <div class="row mt-3">
        <div class="col-12">
             <div class="card card-dark">
                    <div class="card-header">
                         <h3 class="card-title mb-0">Receipts</h3>                    
                    </div>

                    <div class="card-body p-0">
                        <vue-good-table
                        :columns="table_data.columns"
                        :rows="table_data.rows"
                        :pagination-options="{
                          enabled: true,
                            perPage: 2,
                            position: 'top',
                            perPageDropdown: [2, 3, 5, 10, 25],
                         }"
                        theme="black-rhino">

                        <template slot="table-row" slot-scope="props">
                             <span  v-if="props.column.field == 'image'">

                                  <lightbox album="" :src="'/storage/receipt_images/'+props.row.image">
                                  <b-img class="receipt_thumbnails" center thumbnail :src="'/storage/receipt_images/'+props.row.image" width="250" fluid alt="Fluid image" />
                                 </lightbox>
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
        data:function(){
            return {
                table_data:{
                    columns:[
                         {
                            label:'ID',
                            field:'id',
                            type:'number'
                        },
                        {
                            label:'User',
                            field:'user.name',
                            type:'string'
                        },
                         {
                            label:'Receipt',
                            field:'image',
                            sortable:false,
                         },
                        {
                            label:'Description',
                            field:'desc',
                            type:'string'
                        },
                        {
                            label:'Date created',
                            field:'created_at',
                        },
                    ],
                    rows:[]
                }
            }
        },
        methods:{
            loadTableData(){
                this.$Progress.start();
                axios.get('/api/receipts').then(response => {
                    this.$Progress.finish();
                    this.table_data.rows = response.data;
                }).catch(exception => {
                     this.$Progress.fail();
                     swal({
                        title: "Error:" + exception,
                        type: "error"
                    });
                })
                console.log('loaded');
            }
        },
        created(){
            this.$on('datachanged',this.loadTableData);
            this.$emit('datachanged');
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
