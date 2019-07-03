
<div class="container-padding container-widget">
	<div class="row">
	<div class="col-lg-12">
		<?php echo $this->lib->alert_message();?>
		<div class="panel panel-default">
			<div class="row">
				<div class="col-lg-3">
					<form class="form-inline" onsubmit="return false;">
						<input id="search" results=5  type="search" class="ad-mg-bottom" name="search" value="<?php
						echo $keyword;?>" placeholder="Search User" onkeyup="filter_user_data()">
					</form>
				</div>
				<div class="col-lg-7">
					<div class="col-lg-6">
					
							<form class="form-inline">
								<label>Showing</label>
								<select class="form-control" id="status" class="status" name="status" onchange="filter_user_data()">
									<option title="All active and pending users" value="">All users</option>
									<option <?php if(isset($sel_status) AND $sel_status==1){ echo "selected";}?> value="<?php echo base64_encode(1)?>">Active users (<?php echo $count_active;?>)</option>
									<option <?php if(isset($sel_status) AND $sel_status==3){ echo "selected";}?> value="<?php echo base64_encode(3)?>">Pending users (<?php echo $count_pending;?>)</option>
									<option <?php if(isset($sel_status) AND $sel_status==7){ echo "selected";}?> value="<?php echo base64_encode(7)?>">Suspended users (<?php echo $count_suspended;?>)</option>
									<option <?php if(isset($sel_status) AND $sel_status==2){ echo "selected";}?> value="<?php echo base64_encode(2)?>">Inactive users (<?php echo $count_inactive;?>)</option>
									<option <?php if(isset($sel_status) AND $sel_status==9){ echo "selected";}?> value="<?php echo base64_encode(9)?>">Deleted users (<?php echo $count_deleted;?>)</option>
								</select>
							</form>
					
					</div>
					<div class="col-lg-6">
							<form class="form-inline">
								<label>Membership</label>
								<select class="form-control" id="membership" class="membership" name="membership" onchange="filter_user_data()">
									<option value="">All members</option>
									<option <?php if(isset($sel_membership) AND $sel_membership==2){ echo "selected";}?> value="<?php echo base64_encode(2)?>">Verified (<?php echo $count_verified;?>)</option>
									<option <?php if(isset($sel_membership) AND $sel_membership==3){ echo "selected";}?> value="<?php echo base64_encode(3)?>">Public (<?php echo $count_public;?>)</option>
								</select>
							</form>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12 col-lg-2">
                    <a href="<?php echo base_url('admin/users/add')?>">
                        <button class="btn btn-warning form-btn pull-right"><i class="fa fa-user-plus"></i> Add User</button>
                    </a>
				</div>
			</div>
            <div class="panel-body table-responsive">
			<table class="table table-strip paginated_table">
				<thead>
					<tr>
						<th width="3%">
							Sr /<br>
							<span onclick="$('.sel_del').toggle();" class="text-danger">DELETE</span>
							<button style="display: none;" class="btn btn-sm btn-icon btn-danger del_select"><i class="fa fa-trash"></i></button>
						</th>
						<th>Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th width="7%">Status</th>
						<th width="13%">Action</th>
					</tr>
				</thead>
				<tbody class="users_tbody">
					<?php
					if(!is_array($users)){ ?>
						<tr>
							<td colspan="6"><div class="alert alert-warning text-center">No record found for this search!</div></td>
						</tr>
					<?php }else{
						$i=1;
						foreach($users as $user):
								include("components/users/user_list_row.php");
						endforeach;
					}
					?>
				</tbody>
			</table>
			<?php 
            //$this->js->table_pagination(40,'paginated_table',TRUE);
            if(isset($users) && is_array($users)){
                ?>
                <div id="load_more" class="col-lg-12">
                    <?php if(count($users)>=20){?>
                        <p class="text-center"><span class="load_more_btn btn btn-primary btn-block" data-is_post="no">Load More</span></p>
                    <?php }?>
                    <div class="clearfix"></div>
                    <p class="text-center"><span class="feed_loader hidden btn btn-light btn-block"><i class="fa fa-spinner fa-pulse"></i> Please wait... </span></p>
                    <div class="clearfix"></div>
                    <div class="last_msg hidden"><?php $this->lib->display_alert('Looks like you\'ve reached the end of the list.');?></div>
                    <div class="is_end hidden"></div>
                    <br>
                </div>
                <?php
            }
			?>
		</div>
	</div>
    </div>
	<div class="clearfix"></div>
</div>





