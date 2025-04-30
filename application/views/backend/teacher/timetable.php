<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo get_phrase('teacher_timetable'); ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <strong><?php echo get_phrase('teacher'); ?>:</strong> 
                    <?php 
                        $teacher_name = $this->db->get_where('teacher', array('teacher_id' => $this->session->userdata('teacher_id')))->row()->name;
                        echo $teacher_name;
                    ?>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <select name="day_filter" id="day_filter" class="form-control">
                                    <option value=""><?php echo get_phrase('filter_by_day'); ?></option>
                                    <option value="monday"><?php echo get_phrase('monday'); ?></option>
                                    <option value="tuesday"><?php echo get_phrase('tuesday'); ?></option>
                                    <option value="wednesday"><?php echo get_phrase('wednesday'); ?></option>
                                    <option value="thursday"><?php echo get_phrase('thursday'); ?></option>
                                    <option value="friday"><?php echo get_phrase('friday'); ?></option>
                                    <option value="saturday"><?php echo get_phrase('saturday'); ?></option>
                                    <option value="sunday"><?php echo get_phrase('sunday'); ?></option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="search_timetable" placeholder="<?php echo get_phrase('search_timetable'); ?>">
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" id="reset_filters" style="width: 100%;">
                                    <i class="fa fa-refresh"></i> <?php echo get_phrase('reset'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="timetable_table">
                        <thead>
                            <tr>
                                <th><?php echo get_phrase('class'); ?></th>
                                <th><?php echo get_phrase('section'); ?></th>
                                <th><?php echo get_phrase('subject'); ?></th>
                                <th><?php echo get_phrase('day'); ?></th>
                                <th><?php echo get_phrase('time'); ?></th>
                                <th><?php echo get_phrase('room'); ?></th>
                                <th><?php echo get_phrase('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $teacher_id = $this->session->userdata('teacher_id');
                            
                            $this->db->where('teacher_id', $teacher_id);
                            $this->db->order_by('day', 'ASC');
                            $this->db->order_by('starting_time', 'ASC');
                            $timetables = $this->db->get('timetable')->result_array();
                            
                            foreach ($timetables as $row):
                                // Get class name
                                $class_name = $this->db->get_where('class', array('class_id' => $row['class_id']))->row()->name;
                                
                                // Get section name
                                $section_name = $this->db->get_where('section', array('section_id' => $row['section_id']))->row()->name;
                                
                                // Get subject name
                                $subject_name = $this->db->get_where('subject', array('subject_id' => $row['subject_id']))->row()->name;
                            ?>
                            <tr>
                                <td><?php echo $class_name; ?></td>
                                <td><?php echo $section_name; ?></td>
                                <td><?php echo $subject_name; ?></td>
                                <td><?php echo ucfirst($row['day']); ?></td>
                                <td><?php echo $row['starting_time'] . ' - ' . $row['ending_time']; ?></td>
                                <td><?php echo $row['room_number']; ?></td>
                                <td>
                                    <a href="<?php echo base_url(); ?>teacher/class_timetable/view/<?php echo $row['class_id']; ?>" class="btn btn-info btn-xs">
                                        <i class="fa fa-eye"></i> <?php echo get_phrase('view_class_timetable'); ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (empty($timetables)): ?>
                <div class="alert alert-info">
                    <?php echo get_phrase('no_timetable_found'); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Immediately hide any loading overlays when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Teacher timetable loaded');
        hideAllLoaders();
        initializeDataTable();
    });
    
    // Function to hide all possible loaders
    function hideAllLoaders() {
        var loaders = document.querySelectorAll('.loading-overlay, .loader, .ajax-loader, #loading-message, .loading, [class*="loading"], [id*="loading"], [class*="loader"], [id*="loader"]');
        console.log('Hiding ' + loaders.length + ' loaders');
        loaders.forEach(function(loader) {
            loader.style.display = 'none';
            loader.style.visibility = 'hidden';
        });
    }
    
    // Initialize DataTable with error handling
    function initializeDataTable() {
        try {
            console.log('Initializing DataTable');
            
            // Initialize DataTable with sorting and pagination
            var table = $('#timetable_table').DataTable({
                "ordering": true,
                "paging": true,
                "info": true,
                "lengthChange": true,
                "searching": true,
                "dom": '<"top"f>rt<"bottom"lip><"clear">',
                "language": {
                    "search": "<?php echo get_phrase('search'); ?>:",
                    "paginate": {
                        "previous": "<?php echo get_phrase('previous'); ?>",
                        "next": "<?php echo get_phrase('next'); ?>"
                    },
                    "info": "<?php echo get_phrase('showing'); ?> _START_ <?php echo get_phrase('to'); ?> _END_ <?php echo get_phrase('of'); ?> _TOTAL_ <?php echo get_phrase('entries'); ?>",
                    "lengthMenu": "<?php echo get_phrase('show'); ?> _MENU_ <?php echo get_phrase('entries'); ?>"
                },
                "initComplete": function(settings, json) {
                    console.log('DataTable initialization complete');
                    // Hide any loaders that might be active
                    hideAllLoaders();
                }
            });
            
            // Custom filtering for day
            $('#day_filter').on('change', function() {
                table.column(3).search($(this).val()).draw();
            });
            
            // Custom search box
            $('#search_timetable').on('keyup', function() {
                table.search($(this).val()).draw();
            });
            
            // Reset filters
            $('#reset_filters').on('click', function() {
                $('#day_filter').val('');
                $('#search_timetable').val('');
                table.search('').columns().search('').draw();
            });
            
            // Add color-coding for days
            $('#timetable_table tbody tr').each(function() {
                var day = $(this).find('td:nth-child(4)').text().toLowerCase();
                switch(day) {
                    case 'monday':
                        $(this).addClass('bg-light-blue');
                        break;
                    case 'tuesday':
                        $(this).addClass('bg-light-green');
                        break;
                    case 'wednesday':
                        $(this).addClass('bg-light-purple');
                        break;
                    case 'thursday':
                        $(this).addClass('bg-light-yellow');
                        break;
                    case 'friday':
                        $(this).addClass('bg-light-orange');
                        break;
                    case 'saturday':
                        $(this).addClass('bg-light-pink');
                        break;
                    case 'sunday':
                        $(this).addClass('bg-light-gray');
                        break;
                }
            });
            
            console.log('DataTable loaded successfully');
        } catch (e) {
            console.error('Error initializing DataTable: ' + e.message);
            hideAllLoaders();
            // Show error message if DataTable fails to load
            var tableContainer = document.querySelector('.table-responsive');
            if (tableContainer) {
                tableContainer.innerHTML = '<div class="alert alert-danger"><?php echo get_phrase("error_loading_timetable"); ?>: ' + e.message + '</div>';
            }
        }
    }
    
    // Handle AJAX modal with error handling
    function showAjaxModal(url) {
        try {
            console.log('Opening modal: ' + url);
            // Add timestamp to prevent caching
            if (url.indexOf('?') > -1) {
                url = url + '&_=' + new Date().getTime();
            } else {
                url = url + '?_=' + new Date().getTime();
            }
            
            // jQuery Ajax call
            $.ajax({
                url: url,
                success: function(response) {
                    jQuery('#modal_ajax .modal-body').html(response);
                    jQuery('#modal_ajax').modal('show', {backdrop: 'true'});
                },
                error: function(xhr, status, error) {
                    console.error('Error loading modal: ' + error);
                    alert('<?php echo get_phrase("error_loading_modal"); ?>: ' + error);
                }
            });
        } catch (e) {
            console.error('Error in showAjaxModal: ' + e.message);
        }
    }
    
    // Call this function periodically to ensure loaders are hidden
    setInterval(hideAllLoaders, 2000);
</script>

<style>
/* Force hide any loading effects that may be causing issues */
.loading-overlay, .loader, .ajax-loader, #loading-message, .loading, 
[class*="loading"], [id*="loading"], [class*="loader"], [id*="loader"] {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    z-index: -999 !important;
}

/* Light background colors for each day */
.bg-light-blue { background-color: rgba(135, 206, 250, 0.2) !important; }
.bg-light-green { background-color: rgba(144, 238, 144, 0.2) !important; }
.bg-light-purple { background-color: rgba(221, 160, 221, 0.2) !important; }
.bg-light-yellow { background-color: rgba(255, 255, 224, 0.2) !important; }
.bg-light-orange { background-color: rgba(255, 228, 196, 0.2) !important; }
.bg-light-pink { background-color: rgba(255, 182, 193, 0.2) !important; }
.bg-light-gray { background-color: rgba(211, 211, 211, 0.2) !important; }

/* Improve the filter section spacing */
.form-group {
    margin-bottom: 0;
}

/* Custom style for DataTable controls */
.dataTables_wrapper .dataTables_length, 
.dataTables_wrapper .dataTables_filter, 
.dataTables_wrapper .dataTables_info, 
.dataTables_wrapper .dataTables_paginate {
    padding: 10px 0;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.3em 0.8em;
}
</style> 