<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('my_class_timetable'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="month"><?php echo get_phrase('month'); ?></label>
                            <select id="month" class="form-control">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php if ($i == date('n')) echo 'selected'; ?>>
                                    <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="year"><?php echo get_phrase('year'); ?></label>
                            <select id="year" class="form-control">
                                <?php 
                                $current_year = date('Y');
                                for ($y = $current_year - 1; $y <= $current_year + 1; $y++): 
                                ?>
                                <option value="<?php echo $y; ?>" <?php if ($y == $current_year) echo 'selected'; ?>>
                                    <?php echo $y; ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button id="load_timetable" class="btn btn-primary btn-block">
                                <?php echo get_phrase('load_timetable'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="timetable_container" class="timetable-container">
                    <table class="table table-bordered timetable-table">
                        <thead>
                            <tr>
                                <th class="time-col"><?php echo get_phrase('time_slot'); ?></th>
                                <th><?php echo get_phrase('monday'); ?></th>
                                <th><?php echo get_phrase('tuesday'); ?></th>
                                <th><?php echo get_phrase('wednesday'); ?></th>
                                <th><?php echo get_phrase('thursday'); ?></th>
                                <th><?php echo get_phrase('friday'); ?></th>
                                <th><?php echo get_phrase('saturday'); ?></th>
                                <th><?php echo get_phrase('sunday'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="timetable_body">
                            <tr>
                                <td colspan="8" class="text-center"><?php echo get_phrase('select_month_and_year_to_load_timetable'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editModalLabel"><?php echo get_phrase('edit_timetable_entry'); ?></h4>
            </div>
            <div class="modal-body">
                <form id="edit_timetable_form">
                    <input type="hidden" id="edit_id" name="id">
                    
                    <div class="form-group">
                        <label for="edit_room_number"><?php echo get_phrase('room_number'); ?></label>
                        <input type="text" class="form-control" id="edit_room_number" name="room_number" placeholder="<?php echo get_phrase('room_number'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_notes"><?php echo get_phrase('notes'); ?></label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3" placeholder="<?php echo get_phrase('additional_notes'); ?>"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('close'); ?></button>
                <button type="button" class="btn btn-primary" id="save_edit"><?php echo get_phrase('save_changes'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="deleteModalLabel"><?php echo get_phrase('confirm_delete'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo get_phrase('are_you_sure_you_want_to_delete_this_timetable_entry'); ?>?</p>
                <input type="hidden" id="delete_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('cancel'); ?></button>
                <button type="button" class="btn btn-danger" id="confirm_delete"><?php echo get_phrase('delete'); ?></button>
            </div>
        </div>
    </div>
</div>

<style>
    .timetable-container {
        overflow-x: auto;
    }
    
    .timetable-table {
        min-width: 1000px;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .timetable-table thead th {
        text-align: center;
        font-weight: 600;
        padding: 12px 8px;
        color: #fff;
        background-color: #337ab7;
    }
    
    .time-col {
        width: 150px;
        background-color: #337ab7 !important;
        color: white;
    }
    
    .timetable-cell {
        height: 100px;
        vertical-align: top;
        padding: 5px !important;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        transition: all 0.2s ease;
    }
    
    .time-slot-row {
        height: 100px;
    }
    
    .time-display {
        font-weight: bold;
        font-size: 13px;
        text-align: center;
        background-color: #f5f5f5;
        padding: 5px;
        border-radius: 3px;
    }
    
    .subject-name {
        font-weight: bold;
        font-size: 13px;
        color: #333;
        margin-bottom: 5px;
    }
    
    .teacher-name {
        font-size: 12px;
        color: #666;
        font-style: italic;
    }
    
    .room-number {
        font-size: 11px;
        color: #888;
        margin-top: 5px;
    }
    
    .class-section {
        font-size: 12px;
        color: #555;
        margin-bottom: 5px;
    }
    
    .editable-cell {
        min-height: 90px;
        display: flex;
        flex-direction: column;
    }
    
    .actions {
        margin-top: auto;
        text-align: right;
    }
    
    .mb-3 {
        margin-bottom: 15px;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        var timetableData = [];
        
        // Load timetable when button is clicked
        $('#load_timetable').on('click', function() {
            loadTimetable();
        });
        
        // Initial load
        loadTimetable();
        
        // Function to load timetable data
        function loadTimetable() {
            var month = $('#month').val();
            var year = $('#year').val();
            
            $.ajax({
                url: '<?php echo site_url('teacher/get_teacher_timetable_data'); ?>',
                type: 'POST',
                data: {
                    month: month,
                    year: year
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#timetable_body').html('<tr><td colspan="8" class="text-center"><i class="fa fa-spinner fa-spin"></i> <?php echo get_phrase('loading'); ?>...</td></tr>');
                },
                success: function(data) {
                    timetableData = data;
                    renderTimetable();
                },
                error: function(xhr) {
                    console.error('Error loading timetable:', xhr);
                    $('#timetable_body').html('<tr><td colspan="8" class="text-center text-danger"><?php echo get_phrase('error_loading_timetable'); ?></td></tr>');
                }
            });
        }
        
        // Function to render timetable
        function renderTimetable() {
            if (!timetableData.length) {
                $('#timetable_body').html('<tr><td colspan="8" class="text-center"><?php echo get_phrase('no_timetable_entries_found'); ?></td></tr>');
                return;
            }
            
            // Group entries by time slot
            const timeSlots = {};
            timetableData.forEach(function(entry) {
                const timeKey = `${entry.time_slot_start}-${entry.time_slot_end}`;
                if (!timeSlots[timeKey]) {
                    timeSlots[timeKey] = {
                        start: entry.time_slot_start,
                        end: entry.time_slot_end,
                        days: {
                            monday: null,
                            tuesday: null,
                            wednesday: null,
                            thursday: null,
                            friday: null,
                            saturday: null,
                            sunday: null
                        }
                    };
                }
                timeSlots[timeKey].days[entry.day_of_week.toLowerCase()] = entry;
            });
            
            let html = '';
            Object.keys(timeSlots).sort().forEach(function(timeKey) {
                const slot = timeSlots[timeKey];
                html += `
                    <tr class="time-slot-row">
                        <td class="time-col">
                            <div class="time-display">
                                ${slot.start}<br>to<br>${slot.end}
                            </div>
                        </td>`;
                
                ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(function(day) {
                    const entry = slot.days[day];
                    if (entry) {
                        html += `
                            <td class="timetable-cell">
                                <div class="editable-cell">
                                    <div class="subject-name">${entry.subject_name}</div>
                                    <div class="class-section">${entry.class_name} - ${entry.section_name}</div>
                                    <div class="room-number">Room: ${entry.room_number || 'N/A'}</div>
                                    <div class="actions">
                                        <button type="button" class="btn btn-info btn-xs" onclick="editEntry(${entry.id})">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs" onclick="deleteEntry(${entry.id})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>`;
                    } else {
                        html += `<td class="timetable-cell"></td>`;
                    }
                });
                
                html += '</tr>';
            });
            
            $('#timetable_body').html(html);
        }
        
        // Handle edit form submission
        $('#save_edit').on('click', function() {
            var id = $('#edit_id').val();
            var room_number = $('#edit_room_number').val();
            var notes = $('#edit_notes').val();
            
            $.ajax({
                url: '<?php echo site_url('teacher/edit_calendar_timetable_entry'); ?>',
                type: 'POST',
                data: {
                    id: id,
                    room_number: room_number,
                    notes: notes
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Close the modal
                        $('#editModal').modal('hide');
                        
                        // Show success message
                        $.toast({
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3500,
                            stack: 6
                        });
                        
                        // Reload timetable data
                        loadTimetable();
                    } else {
                        // Show error message
                        $.toast({
                            heading: 'Error',
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 3500,
                            stack: 6
                        });
                    }
                },
                error: function(xhr) {
                    // Show error message
                    $.toast({
                        heading: 'Error',
                        text: 'Failed to save changes',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                }
            });
        });
        
        // Handle delete confirmation
        $('#confirm_delete').on('click', function() {
            var id = $('#delete_id').val();
            
            $.ajax({
                url: '<?php echo site_url('teacher/delete_calendar_timetable_entry'); ?>',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Close the modal
                        $('#deleteModal').modal('hide');
                        
                        // Show success message
                        $.toast({
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3500,
                            stack: 6
                        });
                        
                        // Reload timetable data
                        loadTimetable();
                    } else {
                        // Show error message
                        $.toast({
                            heading: 'Error',
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 3500,
                            stack: 6
                        });
                    }
                },
                error: function(xhr) {
                    // Show error message
                    $.toast({
                        heading: 'Error',
                        text: 'Failed to delete entry',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                }
            });
        });
    });
    
    // Function to open edit modal
    function editEntry(id) {
        // Find entry in timetableData
        var entry = timetableData.find(function(item) {
            return item.id == id;
        });
        
        if (entry) {
            // Populate form
            $('#edit_id').val(entry.id);
            $('#edit_room_number').val(entry.room_number);
            $('#edit_notes').val(entry.notes);
            
            // Show modal
            $('#editModal').modal('show');
        }
    }
    
    // Function to open delete confirmation modal
    function deleteEntry(id) {
        $('#delete_id').val(id);
        $('#deleteModal').modal('show');
    }
</script>