<div class="row" style="margin-bottom: 10px;">
    <div class="col-md-4">
        <input type="text" id="admissionNumberSearch" class="form-control" placeholder="<?php echo get_phrase('search_by_admission_number_code_or_name');?>">
    </div>
    <div class="col-md-2">
        <button type="button" id="searchButton" class="btn btn-primary"><?php echo get_phrase('search');?></button>
    </div>
</div>

<table id="example" class="table display">
                	<thead>
                		<tr>
                    		<th><div>#</div></th>
                            <th><div><?php echo get_phrase('admission_number');?></div></th>
                            <th><div><?php echo get_phrase('student_code');?></div></th>
                            <th><div><?php echo get_phrase('Image');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                    		<th><div><?php echo get_phrase('class');?></div></th>
                    		<th><div><?php echo get_phrase('gender');?></div></th>
                            <th><div><?php echo get_phrase('email');?></div></th>
                            <th><div><?php echo get_phrase('phone');?></div></th>
                    		<th><div><?php echo get_phrase('actions');?></div></th>
						</tr>
					</thead>
                    <tbody>
    
                    <?php $counter = 1; $students =  $this->db->get_where('student', array('class_id' => $class_id))->result_array();
                    foreach($students as $key => $student):?>         
                        <tr>
                            <td><?php echo $counter++;?></td>
                            <td><?php echo $student['admission_number'];?></td>
                            <td><?php echo isset($student['student_code']) ? $student['student_code'] : ''; ?></td>
                            <td><img src="<?php echo $this->crud_model->get_image_url('student', $student['student_id']);?>" class="img-circle" width="30"></td>
                            <td><?php echo $student['name'];?></td>
                            <td><?php echo $this->crud_model->get_type_name_by_id('class', $student['class_id']);?></td>
							<td><?php echo $student['sex'];?></td>
                            <td><?php echo $student['email'];?></td>
                            <td><?php echo $student['phone'];?></td>
							<td>
							
				     <a href="<?php echo base_url();?>admin/edit_student/<?php echo $student['student_id'];?>" ><button type="button" class="btn btn-info btn-circle btn-xs"><i class="fa fa-pencil"></i></button></a>
					 <a href="#" onclick="confirm_modal('<?php echo base_url();?>admin/new_student/delete/<?php echo $student['student_id'];?>');"><button type="button" class="btn btn-danger btn-circle btn-xs"><i class="fa fa-times"></i></button></a>
                     <a onclick="showAjaxModal('<?php echo base_url();?>modal/popup/resetstudentPassword/<?php echo $student['student_id'];?>')" class="btn btn-success btn-circle btn-xs"><i class="fa fa-key"></i></a>

			
                           
        					</td>
                        </tr>
    <?php endforeach;?>
                    </tbody>
                </table>

<script type="text/javascript">
    document.getElementById('searchButton').addEventListener('click', function() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById('admissionNumberSearch');
        filter = input.value.toUpperCase();
        table = document.getElementById('example');
        tr = table.getElementsByTagName('tr');

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 1; i < tr.length; i++) { // Start from 1 to skip table header
            var tdAdmissionNumber = tr[i].getElementsByTagName('td')[1]; // Admission Number
            var tdStudentCode = tr[i].getElementsByTagName('td')[2]; // Student Code
            var tdName = tr[i].getElementsByTagName('td')[4]; // Name
            var found = false;

            if (tdAdmissionNumber) {
                var txtValueAdmission = tdAdmissionNumber.textContent || tdAdmissionNumber.innerText;
                if (txtValueAdmission.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                }
            }
            if (!found && tdStudentCode) {
                var txtValueCode = tdStudentCode.textContent || tdStudentCode.innerText;
                if (txtValueCode.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                }
            }
            if (!found && tdName) {
                var txtValueName = tdName.textContent || tdName.innerText;
                if (txtValueName.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                }
            }

            if (found) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    });

    // Optional: Clear filter when input is empty
    document.getElementById('admissionNumberSearch').addEventListener('keyup', function() {
        if (this.value === "") {
            var table = document.getElementById('example');
            var tr = table.getElementsByTagName('tr');
            for (var i = 1; i < tr.length; i++) {
                tr[i].style.display = "";
            }
        }
    });
</script>