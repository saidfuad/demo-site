<div id="container-fluid" id="page-wrapper">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <br>
            <div class="col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>No</th>
                           <!-- <th>Category Id</th>-->
                            <th>Category Name</th>
                            <th>Category Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $count=1;
                    foreach ($categories as $key => $value) { ?>
                       <tr class="gradeU">
                            <td><?php echo $count; ?></td>
                            <!--<td><?php echo $value->assets_category_id; ?></td>-->
                            <td><?php echo $value->assets_cat_name; ?></td>
                            <td><img src="<?php echo base_url('assets/images/categories') .'/'. $value->assets_cat_image; ?>"  alt="cat-image"/></td>
                             <td><?php echo "<a data-placement='top' data-toggle='tooltip' data-original-title='Delete Category'  href='".base_url('index.php/vehicles/delete_vehicle/'.$value->assets_category_id)
                                            ."'class='btn btn-danger'><span class='fa fa-trash'></span></a> &nbsp;&nbsp;
                                                <a data-placement='top' data-toggle='tooltip' data-original-title='View Category'  href='".base_url('index.php/vehicles/fetch_vehicle/'.$value->assets_category_id)
                                                ."'class='btn btn-success'><span class='fa fa-eye'></span></a>"?></td>
                        </tr>
                    <?php 
                        $count++;
                    }?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>    

<script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js')?>"></script>
<script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>

<script>
// Initialize Loadie for Page Load
    $(document).ready(function() {
        $('#dataTables-example').dataTable();
    });
</script>