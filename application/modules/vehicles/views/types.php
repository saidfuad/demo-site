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
                            <!--<th>Type ID</th>-->
                            <th>Vehicle Type</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $count =1;
                    foreach ($types as $key => $value) { ?>
                       <tr class="gradeU">
                            <td><?php echo $count; ?></td>
                            <!--<td><?php echo $value->assets_type_id; ?></td>-->
                            <td><?php echo $value->assets_type_nm; ?></td>
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