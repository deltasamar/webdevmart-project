<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Employee
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Employee Management</a></li>
        <li class="active">Manage Employee</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <?php if($this->session->flashdata('success')): ?>
          <div class="col-md-12">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $this->session->flashdata('success'); $this->session->set_flashdata('success', "");?>
            </div>
          </div>
        <?php elseif($this->session->flashdata('error')):?>
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Failed!</h4>
                  <?php echo $this->session->flashdata('error'); $this->session->set_flashdata('error', "");?>
            </div>
          </div>
        <?php endif;?>

        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Manage Employee</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Photo</th>
                    <th>Address</th> 
					<th>Designation</th>  	
                    <th>Email</th>                    
                    <th>Salary</th>                   
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    if(isset($content)):
                    $i=1; 
                    foreach($content as $cnt): 
                  ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $cnt['name']; ?></td>
                        <td><img src="<?php echo base_url(); ?>uploads/profile-pic/<?php echo $cnt['pic'] ?>" class="img-circle" width="50px" alt="User Image"></td>
                        <td><?php echo $cnt['address']; ?></td>
						 <td><?php echo $cnt['designation']; ?></td>   	
                        <td><?php echo $cnt['email']; ?></td>                        
                        <td><?php echo $cnt['salary']; ?></td>                       
                        <td>
                          <a href="<?php echo base_url(); ?>edit-staff/<?php echo $cnt['id']; ?>" class="btn btn-success">Edit</a>
                          <a class="dropdown-item delete_data btn btn-danger" href="javascript:void(0)" data-id="<?= $cnt['id'] ?>" data-name="<?= ucwords($cnt['name']) ?>">Delete</a>
						  
                        </td>
                      </tr>
                    <?php 
                      $i++;
                      endforeach;
                      endif; 
                    ?>
                  
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
    $(function(){
       
        $('.delete_data').click(function(){
            var _conf = confirm("Are you sure to delete "+$(this).attr('data-name')+"? This action cannot be undone.")
            if(_conf === true){
                
                $.ajax({
                    url:"<?= base_url("delete-staff") ?>",
                    method:'POST',
                    data:{id:$(this).attr('data-id')},
                    dataType:"json",                    
                    success:function(resp){
						
                        //if(resp.status == 'success'){
                            location.reload();
                        //}else{
                            //console.error(resp)
							//window.alert("Action Failed due to an error occured."); 
                            
                        //}
                    }
                })
            }
        })
        
    })
</script>

    