<?php
/*
Plugin Name: Course Schedule
Description: Use short code [course_schedule]
Version: 1.0
Author: Leed
*/

// Include the shortcode functions file
include_once(plugin_dir_path(__FILE__) . 'shortcode-functions.php');

// Register admin menu
add_action('admin_menu', 'course_schedule_menu');

function course_schedule_menu() {
    add_menu_page('Course Schedule', 'Course Schedule', 'read', 'course-schedule', 'course_schedule_page','dashicons-welcome-learn-more');
}

// Main function to display admin page
function course_schedule_page() {
    ?>
    <style>
        .flex {
            display: flex;
            justify-content: space-between;
        }
        .flex-col {
            flex-direction: column;
        }
        .course-name-row {
            width: 43%;
        }
        .course-schedule-row {
            width: 50%;
        }
        .move-buttons {
            margin: auto 0;
        }
        @media only screen and (max-width: 800px) {
            .flex {
                flex-direction: column;
            }
            .course-name-row, .course-schedule-row {
                width: 100%;
            }
        }
    </style>
    <div class="wrap">
        <h1>Course Schedule</h1><hr>

        <!-- Display form to add new course -->
        <form method="post" action="">
            <?php wp_nonce_field('add_new_course_nonce', 'add_new_course_nonce'); ?>
            <h2>Course Name</h2>
            <label for="new-course-name" class="flex flex-col course-name-row"><b>Course Title</b>
                <input type="text" name="new-course-name" id="new-course-name" required></label>
            <h2>Schedule</h2>
            <div class="course-schedule-row flex">
                <label for="new-days" class="flex flex-col"><b>Days</b>
                    <input type="text" name="new-days" id="new-days" required></label>

                <label for="new-dates" class="flex flex-col"><b>Dates</b>
                    <input type="text" name="new-dates" id="new-dates" required></label>


                <label for="new-time" class="flex flex-col"><b>Time</b>
                    <input type="text" name="new-time" id="new-time" required></label>

                <label for="new-hours" class="flex flex-col"><b>Hours</b>
                    <input type="text" name="new-hours" id="new-hours" required></label>

                <label class="flex flex-col"> <input type="hidden" name="edit-course-id" id="edit-course-id" value=""><br>
                    <input type="submit" name="add-new-course" id="add-new-course-btn" class="button button-primary" value="Add New Course"></label></div><br><hr>
        </form>

        <!-- Display HTML table with course schedule -->
        <?php echo course_schedule_table(); ?>

        <script>
            jQuery(document).ready(function ($) {
				       // Delete schedule item
        $('.delete-schedule-item').on('click', function (e) {
            e.preventDefault();
            var courseName = $(this).data('course');
            var itemId = $(this).data('item-id');

            // Reference to the clicked row
            var row = $(this).closest('tr');

            // AJAX request for deleting schedule item
            $.ajax({
                type: 'POST',
                url: ajaxurl, // WordPress AJAX URL
                data: {
                    action: 'delete_schedule_item',
                    course: courseName,
                    item_id: itemId,
                    nonce: '<?php echo wp_create_nonce('delete_schedule_item_nonce'); ?>'
                },
                success: function (response) {
                    console.log('Item deleted:', response);

                    // Remove the row from the table
                    row.remove();
                },
                error: function (error) {
                    console.error('Error deleting item:', error);
                }
            });
        });
                // Edit schedule item
                $('.edit-schedule-item').on('click', function (e) {
                    e.preventDefault();
                    var courseName = $(this).data('course');
                    var itemId = $(this).data('item-id');
                    var dates = $(this).data('dates');
                    var days = $(this).data('days');
                    var time = $(this).data('time');
                    var hours = $(this).data('hours');

                    // Set values to the form fields
                    $('#new-course-name').val(courseName);
                    $('#new-dates').val(dates);
                    $('#new-days').val(days);
                    $('#new-time').val(time);
                    $('#new-hours').val(hours);

                    // Set course ID to a hidden field for identification during submission
                    $('#edit-course-id').val(itemId);

                    // Change button name
                    $('#add-new-course-btn').val('Update Course');
                });

                // Delete schedule item
                $('.delete-schedule-item').on('click', function (e) {
                    e.preventDefault();
                    var courseName = $(this).data('course');
                    var itemId = $(this).data('item-id');

                    // AJAX request for deleting schedule item
                    $.ajax({
                        type: 'POST',
                        url: ajaxurl, // WordPress AJAX URL
                        data: {
                            action: 'delete_schedule_item',
                            course: courseName,
                            item_id: itemId,
                            nonce: '<?php echo wp_create_nonce('delete_schedule_item_nonce'); ?>'
                        },
                        success: function (response) {
                            console.log('Item deleted:', response);
                            // You can update the UI or perform other actions as needed
                        },
                        error: function (error) {
                            console.error('Error deleting item:', error);
                        }
                    });
                });
				// Move course up
$('.move-course-up').on('click', function (e) {
    e.preventDefault();
    var courseId = $(this).data('course');

    // AJAX request for moving course up
    $.ajax({
        type: 'POST',
        url: ajaxurl, // WordPress AJAX URL
        data: {
            action: 'move_course',
            course: courseId,
            direction: 'up',
            nonce: '<?php echo wp_create_nonce('move_course_nonce'); ?>'
        },
        success: function (response) {
            console.log('Course moved up:', response);
            // You can update the UI or perform other actions as needed
            location.reload();
        },
        error: function (error) {
            console.error('Error moving course up:', error);
        }
    });
});
// Move course down
$('.move-course-down').on('click', function (e) {
    e.preventDefault();
    var courseId = $(this).data('course');

    // AJAX request for moving course down
    $.ajax({
        type: 'POST',
        url: ajaxurl, // WordPress AJAX URL
        data: {
            action: 'move_course',
            course: courseId,
            direction: 'down',
            nonce: '<?php echo wp_create_nonce('move_course_nonce'); ?>'
        },
        success: function (response) {
            console.log('Course moved down:', response);
            // You can update the UI or perform other actions as needed
            location.reload();
        },
        error: function (error) {
            console.error('Error moving course down:', error);
        }
    });
});

                // Move schedule item up
                $('.move-schedule-item-up').on('click', function (e) {
                    e.preventDefault();
                    var courseName = $(this).data('course');
                    var itemId = $(this).data('item-id');

                    // AJAX request for moving schedule item up
                    $.ajax({
                        type: 'POST',
                        url: ajaxurl, // WordPress AJAX URL
                        data: {
                            action: 'move_schedule_item',
                            course: courseName,
                            item_id: itemId,
                            direction: 'up',
                            nonce: '<?php echo wp_create_nonce('move_schedule_item_nonce'); ?>'
                        },
                        success: function (response) {
                            console.log('Schedule item moved up:', response);
                            // You can update the UI or perform other actions as needed
                            location.reload();
                        },
                        error: function (error) {
                            console.error('Error moving schedule item up:', error);
                        }
                    });
                });

                // Move schedule item down
                $('.move-schedule-item-down').on('click', function (e) {
                    e.preventDefault();
                    var courseName = $(this).data('course');
                    var itemId = $(this).data('item-id');

                    // AJAX request for moving schedule item down
                    $.ajax({
                        type: 'POST',
                        url: ajaxurl, // WordPress AJAX URL
                        data: {
                            action: 'move_schedule_item',
                            course: courseName,
                            item_id: itemId,
                            direction: 'down',
                            nonce: '<?php echo wp_create_nonce('move_schedule_item_nonce'); ?>'
                        },
                        success: function (response) {
                            console.log('Schedule item moved down:', response);
                            // You can update the UI or perform other actions as needed
                            location.reload();
                        },
                        error: function (error) {
                            console.error('Error moving schedule item down:', error);
                        }
                    });
                });
				
            });
        </script>
    </div>
    <?php
}

// Function to generate HTML table for course schedule
function course_schedule_table() {
    // Fetch the updated data from options
    $response_ary = get_option('course_schedule', array());

    // Generate HTML table
    $str = '';

    if (!empty($response_ary)) {
        foreach ($response_ary as $response_ar) {
            $scheduleItems = $response_ar->scheduleItems;

            if (!empty($scheduleItems)) {
                $str .= '<div class="flex"><h2>' . esc_html($response_ar->name) . '</h2>';
                $str .= '<div class="move-buttons">
                    <a href="#" class="move-course-up" data-course="' . esc_attr($response_ar->name) . '">Move Up</a>
                    <a href="#" class="move-course-down" data-course="' . esc_attr($response_ar->name) . '">Move Down</a>
                </div></div>';
                $str .= '<table class="widefat">
                            <thead>
                                <tr>
                                    <th>Days</th>
                                    <th>Dates</th>
                                    <th>Time</th>
                                    <th>Hours</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>';

                foreach ($scheduleItems as $scheduleItem) {
                    $str .= '<tr>
                                <td>' . esc_html($scheduleItem->days) . '</td>
                                <td>' . esc_html($scheduleItem->dates) . '</td>
                                <td>' . esc_html($scheduleItem->time) . '</td>
                                <td>' . esc_html($scheduleItem->hours) . '</td>
                                <td>
                                    <a href="#" class="edit-schedule-item" 
                                    data-course="' . esc_attr($response_ar->name) . '" 
                                    data-item-id="' . esc_attr($scheduleItem->id) . '"
                                    data-dates="' . esc_attr($scheduleItem->dates) . '"
                                    data-days="' . esc_attr($scheduleItem->days) . '"
                                    data-time="' . esc_attr($scheduleItem->time) . '"
                                    data-hours="' . esc_attr($scheduleItem->hours) . '">Edit</a>
                                    |
                                    <a href="#" class="delete-schedule-item" data-course="' . esc_attr($response_ar->name) . '" data-item-id="' . esc_attr($scheduleItem->id) . '">Delete</a>
                                    |
                                    <a href="#" class="move-schedule-item-up" data-course="' . esc_attr($response_ar->name) . '" data-item-id="' . esc_attr($scheduleItem->id) . '">Move Up</a>
                                    |
                                    <a href="#" class="move-schedule-item-down" data-course="' . esc_attr($response_ar->name) . '" data-item-id="' . esc_attr($scheduleItem->id) . '">Move Down</a>
                                </td>
                            </tr>';
                }

                $str .= '</tbody></table>';
            }
        }
    } else {
        $str = '<p>No courses available.</p>';
    }

    return $str;
}

// Handle form submission
add_action('admin_init', 'handle_form_submission');

function handle_form_submission() {
    if (isset($_POST['add-new-course']) && check_admin_referer('add_new_course_nonce', 'add_new_course_nonce')) {
        $newCourseName = sanitize_text_field($_POST['new-course-name']);
        $newDates = sanitize_text_field($_POST['new-dates']);
        $newDays = sanitize_text_field($_POST['new-days']);
        $newTime = sanitize_text_field($_POST['new-time']);
        $newHours = sanitize_text_field($_POST['new-hours']);

        $response_ary = get_option('course_schedule', array());

        if (isset($_POST['edit-course-id']) && !empty($_POST['edit-course-id'])) {
            // Updating an existing schedule item
            $courseId = sanitize_text_field($_POST['edit-course-id']);
            $existingCourseKey = array_search($newCourseName, array_column($response_ary, 'name'));

            if ($existingCourseKey !== false) {
                // Remove the old schedule item from the existing course
                foreach ($response_ary as &$course) {
                    foreach ($course->scheduleItems as $itemKey => $scheduleItem) {
                        if ($scheduleItem->id === $courseId) {
                            unset($course->scheduleItems[$itemKey]);
                            break;
                        }
                    }
                }

                // Add the updated schedule item to the existing course
                $newScheduleItem = (object) [
                    "id" => uniqid(),
                    "dates" => $newDates,
                    "days" => $newDays,
                    "time" => $newTime,
                    "hours" => $newHours,
                ];

                $response_ary[$existingCourseKey]->scheduleItems[] = $newScheduleItem;
            } else {
                // Handle the case where the course name has changed during an update
                foreach ($response_ary as $key => $course) {
                    foreach ($course->scheduleItems as $itemKey => $scheduleItem) {
                        if ($scheduleItem->id === $courseId) {
                            // Remove the old schedule item
                            unset($response_ary[$key]->scheduleItems[$itemKey]);
                            // Clean up empty schedule items
                            $response_ary[$key]->scheduleItems = array_values($response_ary[$key]->scheduleItems);
                            break;
                        }
                    }
                }

                // Add the updated schedule item to the new course
                $newScheduleItem = (object) [
                    "id" => uniqid(),
                    "dates" => $newDates,
                    "days" => $newDays,
                    "time" => $newTime,
                    "hours" => $newHours,
                ];

                $response_ary[] = (object) [
                    "name" => $newCourseName,
                    "scheduleItems" => [$newScheduleItem],
                ];
            }
        } else {
            // Adding a new schedule item
            $existingCourseKey = array_search($newCourseName, array_column($response_ary, 'name'));

            if ($existingCourseKey !== false) {
                // Add schedule item to the existing course
                $newScheduleItem = (object) [
                    "id" => uniqid(),
                    "dates" => $newDates,
                    "days" => $newDays,
                    "time" => $newTime,
                    "hours" => $newHours,
                ];

                $response_ary[$existingCourseKey]->scheduleItems[] = $newScheduleItem;
            } else {
                // Create a new course with the schedule item
                $newCourse = (object) [
                    "name" => $newCourseName,
                    "scheduleItems" => [
                        (object) [
                            "id" => uniqid(),
                            "dates" => $newDates,
                            "days" => $newDays,
                            "time" => $newTime,
                            "hours" => $newHours,
                        ],
                    ],
                ];

                $response_ary[] = $newCourse;
            }
        }

        // Clean up empty schedule items and courses
        $response_ary = array_values(array_filter($response_ary, function ($course) {
            $course->scheduleItems = array_values(array_filter($course->scheduleItems));
            return !empty($course->scheduleItems);
        }));

        update_option('course_schedule', $response_ary);
        // Display admin notice for add/update
        add_action('admin_notices', function () {
            echo '<div class="notice notice-success is-dismissible">
                    <p>New record added/updated successfully!</p>
                </div>';
        });
    }
}

// AJAX handler for deleting schedule item
add_action('wp_ajax_delete_schedule_item', 'delete_schedule_item_callback');

function delete_schedule_item_callback() {
    check_ajax_referer('delete_schedule_item_nonce', 'nonce');

    if (isset($_POST['course'], $_POST['item_id'])) {
        $courseName = sanitize_text_field($_POST['course']);
        $itemId = sanitize_text_field($_POST['item_id']);

        $response_ary = get_option('course_schedule', array());

        foreach ($response_ary as $key => $course) {
            if ($course->name === $courseName) {
                // Find and remove the schedule item
                foreach ($course->scheduleItems as $itemKey => $scheduleItem) {
                    if ($scheduleItem->id === $itemId) {
                        unset($response_ary[$key]->scheduleItems[$itemKey]);
                        // Clean up empty schedule items
                        $response_ary[$key]->scheduleItems = array_values($response_ary[$key]->scheduleItems);

                        // Clean up empty courses
                        if (empty($response_ary[$key]->scheduleItems)) {
                            unset($response_ary[$key]);
                            $response_ary = array_values($response_ary);
                        }

                        break;
                    }
                }

                break;
            }
        }

        update_option('course_schedule', $response_ary);

        echo 'Item deleted successfully';

        // Output a signal to indicate that the table needs to be refreshed
        echo '<div id="refresh-schedule-table"></div>';
         // Display admin notice for delete
         add_action('admin_notices', function () {
            echo '<div class="notice notice-error is-dismissible">
                    <p>Record deleted successfully!</p>
                </div>';
        });
    }

    wp_die();
}

// AJAX handler for moving schedule item
add_action('wp_ajax_move_schedule_item', 'move_schedule_item_callback');

function move_schedule_item_callback() {
    check_ajax_referer('move_schedule_item_nonce', 'nonce');

    if (isset($_POST['course'], $_POST['item_id'], $_POST['direction'])) {
        $courseName = sanitize_text_field($_POST['course']);
        $itemId = sanitize_text_field($_POST['item_id']);
        $direction = sanitize_text_field($_POST['direction']);

        $response_ary = get_option('course_schedule', array());

        foreach ($response_ary as $key => $course) {
            if ($course->name === $courseName) {
                foreach ($course->scheduleItems as $itemKey => $scheduleItem) {
                    if ($scheduleItem->id === $itemId) {
                        $currentIndex = $itemKey;
                        $newIndex = ($direction === 'up') ? $currentIndex - 1 : $currentIndex + 1;

                        if ($newIndex >= 0 && $newIndex < count($course->scheduleItems)) {
                            $temp = $course->scheduleItems[$currentIndex];
                            $course->scheduleItems[$currentIndex] = $course->scheduleItems[$newIndex];
                            $course->scheduleItems[$newIndex] = $temp;

                            update_option('course_schedule', $response_ary);
                            echo 'Schedule item moved successfully';
                        } else {
                            echo 'Cannot move schedule item beyond boundaries';
                        }

                        break;
                    }
                }

                break;
            }
        }
    }

    wp_die();
}
// AJAX handler for moving course
add_action('wp_ajax_move_course', 'move_course_callback');

function move_course_callback() {
    check_ajax_referer('move_course_nonce', 'nonce');

    if (isset($_POST['course'], $_POST['direction'])) {
        $courseName = sanitize_text_field($_POST['course']);
        $direction = sanitize_text_field($_POST['direction']);

        $response_ary = get_option('course_schedule', array());

        // Find the index of the course in the array
        $courseIndex = array_search($courseName, array_column($response_ary, 'name'));

        if ($courseIndex !== false) {
            // Calculate the new index based on the direction
            $newIndex = ($direction === 'up') ? $courseIndex - 1 : $courseIndex + 1;

            // Check if the new index is within bounds
            if ($newIndex >= 0 && $newIndex < count($response_ary)) {
                // Swap the positions of the courses
                $temp = $response_ary[$courseIndex];
                $response_ary[$courseIndex] = $response_ary[$newIndex];
                $response_ary[$newIndex] = $temp;

                // Update the option with the new order of courses
                update_option('course_schedule', $response_ary);
                echo 'Course moved successfully';
            } else {
                echo 'Cannot move course beyond boundaries';
            }
        } else {
            echo 'Course not found';
        }
    }

    wp_die();
}

