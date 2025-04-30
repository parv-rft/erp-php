<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <?php
                    // Add proper validation for class_id and section_id
                    if (empty($class_id) || !is_numeric($class_id) || empty($section_id) || !is_numeric($section_id)) {
                        redirect(base_url() . 'admin/timetable', 'refresh');
                    }
                    
                    // Get class and section names with error handling
                    $class = $this->db->get_where('class', array('class_id' => $class_id))->row();
                    $section = $this->db->get_where('section', array('section_id' => $section_id))->row();
                    
                    if (!$class || !$section) {
                        redirect(base_url() . 'admin/timetable', 'refresh');
                    }
                    
                    $class_name = $class->name;
                    $section_name = $section->name;
                    ?>
                    <h4><?php echo get_phrase('class_timetable'); ?> - <?php echo $class_name; ?> (<?php echo $section_name; ?>)</h4>
                </div>
            </div>
            <div class="panel-body">
                <!-- Class & Section Selector -->
                <div class="row">
                    <div class="col-md-8">
                        <form action="<?php echo base_url(); ?>admin/timetable_view" method="get" class="form-inline">
                            <div class="form-group">
                                <select name="class_id" class="form-control" id="class_selector" required>
                                    <option value=""><?php echo get_phrase('select_class'); ?></option>
                                    <?php
                                    $classes = $this->db->get('class')->result_array();
                                    foreach ($classes as $row) {
                                        $selected = ($row['class_id'] == $class_id) ? 'selected' : '';
                                        echo '<option value="' . $row['class_id'] . '" ' . $selected . '>' . $row['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="section_id" class="form-control" id="section_selector" required>
                                    <option value=""><?php echo get_phrase('select_section'); ?></option>
                                    <?php
                                    $sections = $this->db->get_where('section', array('class_id' => $class_id))->result_array();
                                    foreach ($sections as $row) {
                                        $selected = ($row['section_id'] == $section_id) ? 'selected' : '';
                                        echo '<option value="' . $row['section_id'] . '" ' . $selected . '>' . $row['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary"><?php echo get_phrase('view_timetable'); ?></button>
                        </form>
                    </div>
                    <div class="col-md-4 text-right" style="margin-bottom: 15px;">
                        <a href="<?php echo base_url(); ?>admin/timetable" class="btn btn-info">
                            <i class="fa fa-arrow-left"></i> <?php echo get_phrase('back_to_timetable'); ?>
                        </a>
                        <button id="add_timetable_btn" class="btn btn-success">
                            <i class="fa fa-plus"></i> <?php echo get_phrase('add_timetable_entry'); ?>
                        </button>
                        <a href="<?php echo base_url(); ?>admin/timetable_print_view/<?php echo $class_id; ?>/<?php echo $section_id; ?>" target="_blank" class="btn btn-primary">
                            <i class="fa fa-print"></i> <?php echo get_phrase('print_timetable'); ?>
                        </a>
                    </div>
                </div>
                
                <!-- Timetable Controls -->
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button class="btn btn-default" id="add_time_slot"><i class="fa fa-plus"></i> <?php echo get_phrase('add_time_slot'); ?></button>
                            <button class="btn btn-default" id="remove_time_slot"><i class="fa fa-minus"></i> <?php echo get_phrase('remove_time_slot'); ?></button>
                            <button class="btn btn-default" id="edit_time_slots"><i class="fa fa-pencil"></i> <?php echo get_phrase('edit_time_slots'); ?></button>
                        </div>
                    </div>
                </div>
                
                <!-- Grid-Based Timetable -->
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        // Check if there are timetable entries
                        $this->db->where('class_id', $class_id);
                        $this->db->where('section_id', $section_id);
                        $timetable_entries = $this->db->get('timetable')->result_array();
                        
                        if (empty($timetable_entries)) {
                            echo '<div class="alert alert-info">';
                            echo get_phrase('no_timetable_entries_found_for_this_class_and_section') . '. ';
                            echo '<a href="javascript:void(0);" id="init_timetable" class="btn btn-primary btn-sm">';
                            echo '<i class="fa fa-plus"></i> ' . get_phrase('initialize_timetable_grid') . '</a>';
                            echo '</div>';
                        }
                        ?>
                        
                        <div class="timetable-container">
                            <table id="timetable-grid" class="table table-bordered timetable-grid">
                            <thead>
                                <tr>
                                        <th class="day-header"><?php echo get_phrase('day_period'); ?></th>
                                    <?php
                                        // Get unique time slots from the database or use default ones
                                        $time_slots = getTimeSlots($class_id, $section_id);
                                        foreach ($time_slots as $slot) {
                                            echo '<th class="time-slot" data-start="' . $slot['start'] . '" data-end="' . $slot['end'] . '">';
                                            echo $slot['start'] . ' - ' . $slot['end'];
                                            echo '</th>';
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                                foreach ($days as $day) {
                                        echo '<tr data-day="' . $day . '">';
                                        echo '<td class="day-name">' . ucfirst($day) . '</td>';
                                        
                                        foreach ($time_slots as $slot) {
                                            $cell_content = '';
                                            $cell_id = '';
                                            $cell_class = 'timetable-cell';
                                            
                                            // Look for an existing entry for this day and time slot
                                            foreach ($timetable_entries as $entry) {
                                                if ($entry['day'] == $day && 
                                                    $entry['starting_time'] == $slot['start'] && 
                                                    $entry['ending_time'] == $slot['end']) {
                                                    
                                                    // Get subject name
                                                    $subject = $this->db->get_where('subject', array('subject_id' => $entry['subject_id']))->row();
                                                    $subject_name = $subject ? $subject->name : get_phrase('unknown_subject');
                                                    
                                                    // Get teacher name
                                                    $teacher = $this->db->get_where('teacher', array('teacher_id' => $entry['teacher_id']))->row();
                                            $teacher_name = $teacher ? $teacher->name : get_phrase('unknown_teacher');
                                            
                                                    $cell_content = '<div class="subject">' . $subject_name . '</div>';
                                                    $cell_content .= '<div class="teacher">' . $teacher_name . '</div>';
                                                    if (!empty($entry['room_number'])) {
                                                        $cell_content .= '<div class="room">Room: ' . $entry['room_number'] . '</div>';
                                                    }
                                                    
                                                    $cell_id = 'timetable-' . $entry['timetable_id'];
                                                    $cell_class .= ' has-entry';
                                                    
                                                    // Add data attributes for editing
                                                    $cell_class .= ' subject-' . str_replace(' ', '-', strtolower($subject_name));
                                                    break;
                                                }
                                            }
                                            
                                            echo '<td id="' . $cell_id . '" class="' . $cell_class . '" ';
                                            echo 'data-day="' . $day . '" data-start="' . $slot['start'] . '" data-end="' . $slot['end'] . '">';
                                            echo $cell_content ? $cell_content : '<div class="empty-cell">' . get_phrase('click_to_add') . '</div>';
                                            echo '</td>';
                                        }
                                        
                                        echo '</tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for adding/editing timetable entries -->
<div class="modal fade" id="timetable_entry_modal" tabindex="-1" role="dialog" aria-labelledby="timetableEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="timetableEntryModalLabel"><?php echo get_phrase('timetable_entry'); ?></h4>
            </div>
            <div class="modal-body">
                <form id="timetable_entry_form" class="form-horizontal">
                    <input type="hidden" name="timetable_id" id="timetable_id" value="">
                    <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                    <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                    <input type="hidden" name="day" id="day" value="">
                    <input type="hidden" name="starting_time" id="starting_time" value="">
                    <input type="hidden" name="ending_time" id="ending_time" value="">
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('subject'); ?></label>
                        <div class="col-sm-9">
                            <select name="subject_id" id="subject_id" class="form-control" required>
                                <option value=""><?php echo get_phrase('select_subject'); ?></option>
                                <?php
                                $subjects = $this->db->get_where('subject', array('class_id' => $class_id))->result_array();
                                foreach ($subjects as $row) {
                                    echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('teacher'); ?></label>
                        <div class="col-sm-9">
                            <select name="teacher_id" id="teacher_id" class="form-control" required>
                                <option value=""><?php echo get_phrase('select_teacher'); ?></option>
                                <?php
                                $teachers = $this->db->get('teacher')->result_array();
                                foreach ($teachers as $row) {
                                    echo '<option value="' . $row['teacher_id'] . '">' . $row['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('room_number'); ?></label>
                        <div class="col-sm-9">
                            <input type="text" name="room_number" id="room_number" class="form-control" placeholder="<?php echo get_phrase('room_number'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('close'); ?></button>
                <button type="button" class="btn btn-danger" id="delete_entry_btn"><?php echo get_phrase('delete'); ?></button>
                <button type="button" class="btn btn-primary" id="save_entry_btn"><?php echo get_phrase('save'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for editing time slots -->
<div class="modal fade" id="time_slots_modal" tabindex="-1" role="dialog" aria-labelledby="timeSlotsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="timeSlotsModalLabel"><?php echo get_phrase('edit_time_slots'); ?></h4>
            </div>
            <div class="modal-body">
                <div id="time_slots_container">
                    <?php foreach ($time_slots as $index => $slot) { ?>
                    <div class="time-slot-row form-inline">
                        <div class="form-group">
                            <input type="time" class="form-control time-start" value="<?php echo $slot['start']; ?>">
                        </div>
                        <span class="time-separator">-</span>
                        <div class="form-group">
                            <input type="time" class="form-control time-end" value="<?php echo $slot['end']; ?>">
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-slot"><i class="fa fa-times"></i></button>
                    </div>
                    <?php } ?>
                </div>
                <button type="button" class="btn btn-default" id="add_new_slot">
                    <i class="fa fa-plus"></i> <?php echo get_phrase('add_time_slot'); ?>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('close'); ?></button>
                <button type="button" class="btn btn-primary" id="save_time_slots"><?php echo get_phrase('save_changes'); ?></button>
            </div>
        </div>
    </div>
</div>

<style>
/* Timetable grid styles */
.timetable-container {
    overflow-x: auto;
    margin-top: 20px;
}

.timetable-grid {
    min-width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
}

.timetable-grid th, .timetable-grid td {
    text-align: center;
    vertical-align: middle;
    border: 1px solid #ddd;
    padding: 8px;
    position: relative;
}

.day-header {
    width: 100px;
    background-color: #f5f5f5;
    font-weight: bold;
}

.time-slot {
    min-width: 120px;
    background-color: #f5f5f5;
    font-weight: bold;
}

.day-name {
    font-weight: bold;
    background-color: #f5f5f5;
}

.timetable-cell {
    height: 80px;
    padding: 5px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.timetable-cell:hover {
    background-color: #f9f9f9;
}

.timetable-cell.has-entry {
    background-color: #e8f4f8;
}

.subject {
    font-weight: bold;
    margin-bottom: 3px;
}

.teacher {
    font-size: 0.9em;
    color: #555;
}

.room {
    font-size: 0.8em;
    color: #777;
    margin-top: 3px;
}

.empty-cell {
    color: #aaa;
    font-style: italic;
    font-size: 0.9em;
}

/* Time slot editing styles */
.time-slot-row {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
}

.time-separator {
    margin: 0 10px;
}

.remove-slot {
    margin-left: 10px;
}

/* Subject color coding */
.subject-mathematics { background-color: #d1ecf1; }
.subject-science { background-color: #d4edda; }
.subject-english { background-color: #fff3cd; }
.subject-history { background-color: #f8d7da; }
.subject-art { background-color: #e0cffc; }
.subject-music { background-color: #ffedf2; }
.subject-physical-education { background-color: #c2f0ea; }
</style>

<script type="text/javascript">
$(document).ready(function() {
    console.log('Initializing timetable grid');
    
    // Handle class change - update sections
    $('#class_selector').on('change', function() {
        var class_id = $(this).val();
        
        if (class_id) {
            $.ajax({
                url: '<?php echo base_url(); ?>admin/get_class_section/' + class_id,
                success: function(response) {
                    $('#section_selector').html(response);
                }
            });
        } else {
            $('#section_selector').html('<option value=""><?php echo get_phrase("select_section"); ?></option>');
        }
    });
    
    // Initialize timetable grid
    $('#init_timetable').on('click', function() {
        if (confirm('<?php echo get_phrase("initialize_empty_timetable_grid"); ?>?')) {
            location.reload();
        }
    });
    
    // Handle clicking on a timetable cell
    $('.timetable-cell').on('click', function() {
        var day = $(this).data('day');
        var start = $(this).data('start');
        var end = $(this).data('end');
        var cellId = $(this).attr('id');
        
        // Set form values
        $('#day').val(day);
        $('#starting_time').val(start);
        $('#ending_time').val(end);
        
        // Check if cell has an entry
        if (cellId && cellId.startsWith('timetable-')) {
            var timetableId = cellId.replace('timetable-', '');
            $('#timetable_id').val(timetableId);
            $('#timetableEntryModalLabel').text('<?php echo get_phrase("edit_timetable_entry"); ?>');
            $('#delete_entry_btn').show();
            
            // Load entry data
            loadTimetableEntry(timetableId);
        } else {
            $('#timetable_id').val('');
            $('#timetableEntryModalLabel').text('<?php echo get_phrase("add_timetable_entry"); ?>');
            $('#delete_entry_btn').hide();
            
            // Reset form
            $('#subject_id').val('');
            $('#teacher_id').val('');
            $('#room_number').val('');
        }
        
        // Show modal
        $('#timetable_entry_modal').modal('show');
    });
    
    // Handle add timetable button click
    $('#add_timetable_btn').on('click', function() {
        $('#timetable_id').val('');
        $('#day').val('');
        $('#starting_time').val('');
        $('#ending_time').val('');
        $('#subject_id').val('');
        $('#teacher_id').val('');
        $('#room_number').val('');
        
        $('#timetableEntryModalLabel').text('<?php echo get_phrase("add_timetable_entry"); ?>');
        $('#delete_entry_btn').hide();
        
        $('#timetable_entry_modal').modal('show');
    });
    
    // Handle save entry button click
    $('#save_entry_btn').on('click', function() {
        var formData = $('#timetable_entry_form').serialize();
        
        // Validate form
        if (!$('#subject_id').val() || !$('#teacher_id').val() || !$('#day').val() || 
            !$('#starting_time').val() || !$('#ending_time').val()) {
            alert('<?php echo get_phrase("please_fill_all_required_fields"); ?>');
            return;
        }
        
        // Show loading
        showLoading();
        
        // Save via AJAX
        $.ajax({
            url: '<?php echo base_url(); ?>admin/save_timetable_entry_ajax',
            type: 'POST',
            data: formData,
            success: function(response) {
                hideLoading();
                var result = JSON.parse(response);
                
                if (result.status == 'success') {
                    // Success message
                    $.toast({
                        heading: '<?php echo get_phrase("success"); ?>',
                        text: result.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3500
                    });
                    
                    // Close modal and reload
                    $('#timetable_entry_modal').modal('hide');
                    location.reload();
                } else {
                    // Error message
                    $.toast({
                        heading: '<?php echo get_phrase("error"); ?>',
                        text: result.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3500
                    });
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                
                // Error message
                $.toast({
                    heading: '<?php echo get_phrase("error"); ?>',
                    text: '<?php echo get_phrase("an_error_occurred"); ?>: ' + error,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3500
                });
            }
        });
    });
    
    // Handle delete entry button click
    $('#delete_entry_btn').on('click', function() {
        var timetable_id = $('#timetable_id').val();
        
        if (!timetable_id) return;
        
        // Confirm delete
        if (confirm('<?php echo get_phrase("are_you_sure_you_want_to_delete_this_entry"); ?>?')) {
            deleteTimeTableEntry(timetable_id);
        }
    });
    
    // Handle edit time slots button
    $('#edit_time_slots').on('click', function() {
        $('#time_slots_modal').modal('show');
    });
    
    // Add new time slot
    $('#add_new_slot').on('click', function() {
        // Show modal with just the add slot functionality
        $('#time_slots_modal').modal('show');
    });
    
    // Handle remove time slot button click
    $('#remove_time_slot').on('click', function() {
        if (confirm('<?php echo get_phrase("remove_last_time_slot"); ?>?')) {
            // Remove the last column from each row
            var columnCount = $('#timetable-grid thead tr th').length;
            if (columnCount > 2) { // Keep at least one time slot
                $('#timetable-grid tr').each(function() {
                    $(this).find('th:last-child, td:last-child').remove();
                });
                
                // Save the updated time slots
                saveTimeSlots();
            } else {
                alert('<?php echo get_phrase("cannot_remove_all_time_slots"); ?>');
            }
        }
    });
    
    // Save time slots
    $('#save_time_slots').on('click', function() {
        var timeSlots = [];
        
        // Collect all time slots
        $('.time-slot-row').each(function() {
            var start = $(this).find('.time-start').val();
            var end = $(this).find('.time-end').val();
            
            if (start && end) {
                timeSlots.push({
                    start: start,
                    end: end
                });
            }
        });
        
        // Validate
        if (timeSlots.length === 0) {
            alert('<?php echo get_phrase("please_add_at_least_one_time_slot"); ?>');
            return;
        }
        
        // Show loading
        showLoading();
        
        // Save via AJAX
        $.ajax({
            url: '<?php echo base_url(); ?>admin/save_time_slots_ajax',
            type: 'POST',
            data: {
                class_id: <?php echo $class_id; ?>,
                section_id: <?php echo $section_id; ?>,
                time_slots: JSON.stringify(timeSlots)
            },
            success: function(response) {
                hideLoading();
                var result = JSON.parse(response);
                
                if (result.status == 'success') {
                    // Success message
                    $.toast({
                        heading: '<?php echo get_phrase("success"); ?>',
                        text: result.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3500
                    });
                    
                    // Close modal and reload
                    $('#time_slots_modal').modal('hide');
                    location.reload();
                } else {
                    // Error message
                    $.toast({
                        heading: '<?php echo get_phrase("error"); ?>',
                        text: result.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3500
                    });
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                
                // Error message
                $.toast({
                    heading: '<?php echo get_phrase("error"); ?>',
                    text: '<?php echo get_phrase("an_error_occurred"); ?>: ' + error,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3500
                });
            }
        });
    });
});

// Function to load timetable entry data
function loadTimetableEntry(timetableId) {
    // Show loading
    showLoading();
    
    // Get entry data via AJAX
    $.ajax({
        url: '<?php echo base_url(); ?>admin/get_timetable_entry_ajax/' + timetableId,
        type: 'GET',
        success: function(response) {
            hideLoading();
            var entry = JSON.parse(response);
            
            if (entry) {
                $('#subject_id').val(entry.subject_id);
                $('#teacher_id').val(entry.teacher_id);
                $('#room_number').val(entry.room_number);
                $('#day').val(entry.day);
                $('#starting_time').val(entry.starting_time);
                $('#ending_time').val(entry.ending_time);
            }
        },
        error: function(xhr, status, error) {
            hideLoading();
            alert('<?php echo get_phrase("error_loading_data"); ?>: ' + error);
        }
    });
}

// Function to delete timetable entry
function deleteTimeTableEntry(timetableId) {
    // Show loading overlay
    showLoading();
    
    // Send AJAX request to delete entry
    $.ajax({
        url: '<?php echo base_url(); ?>admin/delete_timetable_ajax/' + timetableId,
        type: 'GET',
                success: function(response) {
            hideLoading();
            var result = JSON.parse(response);
            
            if (result.status == 'success') {
                // Success message
                $.toast({
                    heading: '<?php echo get_phrase("success"); ?>',
                    text: result.message,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 3500
                });
                
                // Close modal and reload
                $('#timetable_entry_modal').modal('hide');
                location.reload();
            } else {
                // Error message
                $.toast({
                    heading: '<?php echo get_phrase("error"); ?>',
                    text: result.message,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3500
                });
            }
                },
                error: function(xhr, status, error) {
            hideLoading();
            
            // Error message
            $.toast({
                heading: '<?php echo get_phrase("error"); ?>',
                text: '<?php echo get_phrase("an_error_occurred"); ?>: ' + error,
                position: 'top-right',
                loaderBg: '#ff6849',
                icon: 'error',
                hideAfter: 3500
            });
        }
    });
}

// Function to save time slots
function saveTimeSlots() {
    var timeSlots = [];
    
    // Get time slots from the table headers
    $('#timetable-grid thead tr th.time-slot').each(function() {
        timeSlots.push({
            start: $(this).data('start'),
            end: $(this).data('end')
        });
    });
    
    // Save via AJAX
    $.ajax({
        url: '<?php echo base_url(); ?>admin/save_time_slots_ajax',
        type: 'POST',
        data: {
            class_id: <?php echo $class_id; ?>,
            section_id: <?php echo $section_id; ?>,
            time_slots: JSON.stringify(timeSlots)
        },
        success: function(response) {
            // Success handling is minimal here since this is called from other functions
            var result = JSON.parse(response);
            
            if (result.status != 'success') {
                alert('<?php echo get_phrase("error_saving_time_slots"); ?>: ' + result.message);
            }
        },
        error: function(xhr, status, error) {
            alert('<?php echo get_phrase("error_saving_time_slots"); ?>: ' + error);
        }
    });
}

// Helper function to show loading overlay
function showLoading() {
    if ($('#loading-overlay').length === 0) {
        $('body').append('<div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;"><div style="background: white; padding: 20px; border-radius: 5px;"><i class="fa fa-spinner fa-spin fa-2x"></i> <?php echo get_phrase("loading"); ?>...</div></div>');
    } else {
        $('#loading-overlay').show();
    }
}

// Helper function to hide loading overlay
function hideLoading() {
    $('#loading-overlay').hide();
}
</script>

<?php
/**
 * Helper function to get time slots for the timetable
 */
function getTimeSlots($class_id, $section_id) {
    $CI =& get_instance();
    
    // Try to get saved time slots
    $CI->db->where('class_id', $class_id);
    $CI->db->where('section_id', $section_id);
    $saved_slots = $CI->db->get('class_time_slots')->row();
    
    if ($saved_slots && !empty($saved_slots->time_slots)) {
        return json_decode($saved_slots->time_slots, true);
    }
    
    // Default time slots if none are saved
    return array(
        array('start' => '08:00', 'end' => '08:45'),
        array('start' => '08:45', 'end' => '09:30'),
        array('start' => '09:30', 'end' => '10:15'),
        array('start' => '10:15', 'end' => '11:00'),
        array('start' => '11:00', 'end' => '11:45'),
        array('start' => '11:45', 'end' => '12:30'),
        array('start' => '13:30', 'end' => '14:15'),
        array('start' => '14:15', 'end' => '15:00')
    );
}
?> 